<?php

namespace Lara\Http\Controllers;

use Config;
use Illuminate\Http\Request;
use Hash;
use Lara\Library\Revision;
use Lara\Person;
use Lara\RevisionEntry;
use Lara\SurveyAnswerCell;
use Session;
use Redirect;
use DateTime;

use Lara\Survey;
use Lara\SurveyQuestion;
use Lara\SurveyAnswer;
use Lara\SurveyAnswerOption;
use Lara\Club;
use Lara\Http\Requests\SurveyRequest;
use Carbon\Carbon;

/**
 * Class SurveyController
 * @package Lara\Http\Controllers
 *
 * RESTful Resource Controller, implements all RESTful actions (index, create, store, show, edit, update, destroy)
 */
class SurveyController extends Controller
{
    /**
     * SurveyController constructor.
     * call middleware to give only authenticated users access to the methods
     */
    public function __construct()
    {
        // reject guests
        $this->middleware('rejectGuests', ['only' => 'create', 'store']);
        // if survey is private, reject guests
        $this->middleware('privateEntry:Lara\Survey,survey', ['except' => ['create', 'store']]);
        // only Ersteller/Admin/Marketing/Clubleitung, privileged user groups only
        $this->middleware('creator:Lara\Survey,survey', ['only' => ['edit', 'update', 'destroy']]);
        // after deadline, only Ersteller/Admin/Marketing/Clubleitung, privileged user groups only
        $this->middleware('deadlineSurvey', ['only' => ['edit', 'update', 'destroy']]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        //prepare correct date and time format to be used in forms for deadline
        $datetime = carbon::now()->endOfDay();
        $time = $datetime->toTimeString();
        $date = $datetime->toDateString();
        //placeholder because createSurveyView needs variable, can set defaults here
        $survey = new Survey();
        return view('createSurveyView', compact('survey', 'time', 'date'));
    }

    /**
     * @param SurveyRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(SurveyRequest $request)
    {
        $survey = new Survey;
        $revision_survey = new Revision($survey);
        $survey->creator_id = Session::get('userId');
        $survey->title = $request->title;
        $survey->description = $request->description;
        $survey->deadline = strftime("%Y-%m-%d %H:%M:%S", strtotime($request->deadlineDate . $request->deadlineTime));
        $survey->is_anonymous = isset($request->is_anonymous);
        $survey->is_private = isset($request->is_private);
        $survey->show_results_after_voting = isset($request->show_results_after_voting);

        //if there is a password make a hash of it and save it
        if (!empty($request->password)
            && !empty($request->password_confirmation)
            && $request->password == $request->password_confirmation
        ) {
            $survey->password = Hash::make($request->password);
        }

        $survey->save();
        $revision_survey->save($survey, "Umfrage erstellt");

        // values will reindex the array with consecutive integers, removing holes
        // (e.g. if a user deletes a question somewhere in the middle)
        $questions = collect($request->questionText)->values();
        $answerOptions = $request->answerOption;

        // convert textInput to int for better processing
        $fieldTypes = array_map(function ($asString) {
            return intval($asString);
        }, $request->type_select);
        $required = $request->required;

        //make new question model instance, fill it and save it

        foreach ($questions as $order => $text) {
            SurveyQuestion::make($survey, $order, $fieldTypes[$order], $required[$order], $text,
                $answerOptions[$order]);
        }

        return Redirect::action('SurveyController@show', array('id' => $survey->id));
    }


    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        //find SurveyID
        $survey = Survey::findorFail($id);

        //find answers and questions that belong to SurveyID
        foreach ($survey->answers as $answer) {
            //find AnswerCells belonging to Answer and delete both
            foreach ($answer->cells as $cell) {
                Revision::deleteWithRevision($cell);
            }
            Revision::deleteWithRevision($answer);
        }

        //find AnswerOptions belonging to Questions and delete both
        foreach ($survey->questions as $question) {
            foreach ($question->options as $answerOption) {
                Revision::deleteWithRevision($answerOption);
            }
            Revision::deleteWithRevision($question);
        }

        //finally delete survey
        Revision::deleteWithRevision($survey);

        Session::put('message', 'Umfrage gelöscht!');
        Session::put('msgType', 'success');

        return Redirect::action('MonthController@currentMonth');
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        //find survey
        $survey = Survey::findOrFail($id);
        //find questions
        $questions = $survey->questions;
        $questionCount = count($questions);
        //find answers
        $answers = $survey->answers;
        //find all clubs
        $clubs = Club::all();

        //get the information from the current session
        $userId = Session::get('userId');
        $userStatus = Session::get("userStatus");

        $userParticipatedAlready = $survey->answers
            ->contains(function ($answer) use ($userId) {
                return $answer && $answer->creator_id === $userId && !empty($userId);
            });

        $answers_with_trashed_ids = SurveyAnswer::withTrashed()
            ->where('survey_id', $survey->id)
            ->get()
            ->map(function ($answer) {
                return $answer->id;
            });

        $revisions_objects = \Lara\Revision::join("revision_object_relations", "revisions.id", "=",
            "revision_object_relations.revision_id")
            ->where("revision_object_relations.object_name", "=", "SurveyAnswer")
            ->whereIn("revision_object_relations.object_id", $answers_with_trashed_ids)
            ->orWhere(function ($query) use ($survey) {
                $query->where("revision_object_relations.object_name", "=", "Survey")
                    ->where("revision_object_relations.object_id", "=", $survey->id);
            })
            ->distinct()
            ->orderBy("created_at", "desc")
            ->get(['creator_id', 'summary', 'created_at', 'revision_id']);

        $revisions = $revisions_objects->toArray();

        foreach ($revisions as &$revision) {
            $creator = Person::where('prsn_ldap_id', '=', $revision['creator_id'])
                ->get(['prsn_name'])
                ->first();
            $isGuest = empty($creator) || is_null($revision['creator_id']);
            $revision['creator_name'] = $isGuest ? trans('guest') : $creator->prsn_name;
            unset($revision['creator_id']);
            $revision['revision_entries'] = RevisionEntry::where('revision_id', '=', $revision['revision_id'])
                ->get(['changed_column_name', 'old_value', 'new_value'])
                ->toArray();
            unset($revision['revision_id']);

            foreach ($revision['revision_entries'] as &$entry) {
                // rename the displayed column names to hide database-schema
                switch ($entry['changed_column_name']) {
                    case "name":
                        $entry['changed_column_name'] = "Name";
                        break;
                    case "club":
                        $entry['changed_column_name'] = "Club";
                        break;
                    case "answer":
                        $entry['changed_column_name'] = "Antwort";
                        break;
                    case "title":
                        $entry['changed_column_name'] = "Titel";
                        break;
                    case "description":
                        $entry['changed_column_name'] = "Beschreibung";
                        break;
                    case "deadline":
                        $entry['changed_column_name'] = "Deadline";
                        break;
                    case "is_anonymous":
                        $entry['changed_column_name'] = "Ergebnisse sind nur für den Umfragenersteller sichtbar?";
                        $entry['old_value'] = $this->booleanIntoText($entry['old_value']);
                        $entry['new_value'] = $this->booleanIntoText($entry['new_value']);
                        break;
                    case "show_results_after_voting":
                        $entry['changed_column_name'] = "Ergebnisse sind erst nach dem Ausfüllen sichtbar?";
                        $entry['old_value'] = $this->booleanIntoText($entry['old_value']);
                        $entry['new_value'] = $this->booleanIntoText($entry['new_value']);
                        break;
                    case "is_private":
                        $entry['changed_column_name'] = "nur für eingeloggte Nutzer sichtbar?";
                        $entry['old_value'] = $this->booleanIntoText($entry['old_value']);
                        $entry['new_value'] = $this->booleanIntoText($entry['new_value']);
                        break;
                    case "question":
                        $entry['changed_column_name'] = "Frage";
                        break;
                    case "field_type":
                        $entry['changed_column_name'] = "Fragetyp";
                        $entry['old_value'] = $this->getFieldTypeName($entry['old_value']);
                        $entry['new_value'] = $this->getFieldTypeName($entry['new_value']);
                        break;
                    case "is_required":
                        $entry['changed_column_name'] = "Pflichtfrage?";
                        $entry['old_value'] = $this->booleanIntoText($entry['old_value']);
                        $entry['new_value'] = $this->booleanIntoText($entry['new_value']);
                        break;
                }
            }
            unset($entry);
        }
        unset($revision);

        //check if the role of the user allows edit/delete for all answers
        $userGroup = Session::get('userGroup');
        $userCanEditDueToRole = collect(['admin', 'marketing', 'clubleitung'])->contains($userGroup);

        //evaluation part that shows below the survey, a statistic of answers of the users who already took part in the survey

        $evaluation = $questions->map(function (SurveyQuestion $question) {
            switch ($question->field_type) {
                case 1:
                    return [];
                case 2:
                    $cells = $question->cells;
                    return collect([
                        'Ja' => $cells->where('answer', 'Ja')->count(),
                        'Nein' => $cells->where('answer', 'Nein')->count(),
                        'Keine Angabe' => $cells->where('answer', 'Keine Angabe')->count()
                    ])->reject(function ($count, $option) use ($question) {
                        return $question->is_required && $option === 'Keine Angabe';
                    })->toArray();
                case 3:
                    $options = $question->options;
                    if ($question->is_required == false) {
                        $prefer_not_to_say = new SurveyAnswerOption();
                        $prefer_not_to_say->answer_option = 'keine Angabe';
                        $options->push($prefer_not_to_say);
                    }

                    return $options->mapWithKeys(function ($option) use ($question) {
                        return [
                            $option->answer_option =>
                                $question->cells
                                    ->where('answer', 'option.answer_option')
                                    ->count()
                        ];
                    })->toArray();
            }
        })->toArray();

        //ignore html tags in the description
        $survey->description = htmlspecialchars($survey->description, ENT_NOQUOTES);
        //if URL is in the description, convert it to clickable hyperlink (<a> tag)
        $survey->description = $this->addLinks($survey->description);

        //return all the gathered information to the survey view
        return view('surveyView', compact('survey', 'questions', 'questionCount', 'answers', 'clubs', 'userId',
            'userGroup', 'userStatus', 'userCanEditDueToRole', 'evaluation', 'revisions', 'userParticipatedAlready'));
    }

    /**
     * @param  int $id
     * @return mixed
     */
    public function edit($id)
    {
        //find survey
        $survey = Survey::findOrFail($id);

        //find questions and answer options
        $questions = $survey->questions;
        foreach ($questions as $question) {
            $answer_options = $question->getAnswerOptions;
        }

        // prepare correct date and time format to be used in forms for deadline
        $date = substr(($survey->deadline), 0, 10);
        $time = substr(($survey->deadline), 11, 8);
        return view('editSurveyView', compact('survey', 'questions', 'answer_options', 'date', 'time'));
    }

    /**
     * @param int $id
     * @param SurveyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update($id, SurveyRequest $request)
    {
        //find survey
        $survey = Survey::findOrFail($id);
        $revision_survey = new Revision($survey);

        //edit existing survey
        $survey->title = $request->title;
        $survey->description = $request->description;

        //format deadline for database
        $survey->deadline = strftime("%Y-%m-%d %H:%M:%S", strtotime($request->deadlineDate . $request->deadlineTime));
        $survey->is_anonymous = isset($request->is_anonymous) ? "1" : "0";
        $survey->is_private = isset($request->is_private) ? "1" : "0";
        $survey->show_results_after_voting = isset($request->show_results_after_voting) ? "1" : "0";

        //delete password if user changes both to delete
        $deletePassword = $request->password == "delete" && $request->password_confirmation == "delete";
        $requestContainsPassword = !empty($request->password)
            && !empty($request->password_confirmation)
            && $request->password == $request->password_confirmation;
        if ($deletePassword) {
            $survey->password = '';
        } else if ($requestContainsPassword) {
            $survey->password = Hash::make($request->password);
        }

        //save the updates
        $survey->save();
        $revision_survey->save($survey, "Umfrage geändert");

        //get questions and answer options as arrays from the input
        $newQuestions = collect($request->questionText);
        $newAnswerOptions = $request->answerOption;

        $required = $newQuestions->map(function($question, $index) use($request) {
            if(isset($request->required) && isset($request->required[$index])) {
                return $request->required[$index]? 1 : 0;
            }
            return 0;
        });


        /* get question type as array
         * 1: text field
         * 2: checkbox
         * 3: dropdown, has answer options!
         */
        $types = collect($request->type_select)->map(function ($asString) {
            return intval($asString);
        });

        $oldQuestions = $survey->questions;
        $oldAnswerOptions = $oldQuestions->map(function ($question) {
            return $question->options;
        });

        $oldQuestionTexts = $oldQuestions->pluck('question');

        $newlyCreatedQuestions = $newQuestions->diffKeys($oldQuestionTexts);
        $deletedQuestions = $oldQuestionTexts->diffKeys($newQuestions);
        $nonModifiedQuestions = $newQuestions->filter(function ($text, $order) use ($oldQuestions, $types, $required, $newAnswerOptions, $oldAnswerOptions) {
            $isSameText = $oldQuestions->get($order)->question === $text;
            if (!$isSameText) {
                return false;
            }
            $isSameType = $oldQuestions->get($order)->field_type === $types[$order];
            if (!$isSameType) {
                return false;
            }
            $isSameRequired = $oldQuestions->get($order)->is_required === $required[$order];
            if (!$isSameRequired) {
                return false;
            }

            if ($types[$order] !== 3) {
                return true;
            }
            $oldOptions = collect($oldAnswerOptions->get($order));
            $newOptions = collect($newAnswerOptions[$order]);

            $noCreatedOptions = $newOptions->intersect($oldOptions)->count() === $newOptions->count();
            $noDeletedOptions = $oldOptions->intersect($newOptions)->count() === $oldOptions->count();
            $noModifiedOptions = $newOptions->diff($oldOptions)->count() === $newOptions->count();

            $areOptionsUnchanged = $noDeletedOptions && $noCreatedOptions && $noModifiedOptions;

            return $areOptionsUnchanged;
        });
        $modifiedQuestions = $oldQuestionTexts->diff($nonModifiedQuestions)->diffKeys($deletedQuestions);

        foreach ($newlyCreatedQuestions as $index => $question) {
            SurveyQuestion::make($survey, $index, $types[$index], $required[$index], $question,
                $newAnswerOptions[$index]);
        }

        foreach ($deletedQuestions as $index => $question) {
            $trashedQuestion = $oldQuestions->get($index);
            $trashedQuestion->options->each(Revision::deleteWithRevision);
            $trashedQuestion->cells->each(Revision::deleteWithRevision);
            Revision::deleteWithRevision($trashedQuestion);
        }

        foreach ($modifiedQuestions as $index => $question) {
            $questionToModify = $oldQuestions->get($index);
            $questionRevision = new Revision($questionToModify);

            $questionToModify->order = $index;
            $questionToModify->field_type = $types[$index];
            $questionToModify->is_required = $required[$index];
            $questionToModify->question = $question;

            if ($types[$index] === 3) {
                $oldOptions = collect($oldAnswerOptions->get($index));
                $newOptions = collect($newAnswerOptions[$index]);

                $optionTexts = $oldOptions->map(function ($option) {
                    return $option->answer_option;
                });
                $deletedOptions = $optionTexts->diffKeys($newOptions);
                $newlyCreatedOptions = $newOptions->diffKeys($optionTexts);
                $unmodifiedOptions = $optionTexts->filter(function($option, $optionIndex) use($newOptions) {
                    return $newOptions[$optionIndex] === $option;
                });
                $modifiedOptions = $optionTexts->diff($unmodifiedOptions);

                foreach($deletedOptions as $optionIndex => $option) {
                    Revision::deleteWithRevision($oldAnswerOptions->get(index)->get($optionIndex));
                }
                foreach($newlyCreatedOptions as $optionIndex => $option) {
                    SurveyAnswerOption::make($questionToModify, $option);
                }
                foreach($modifiedOptions as $optionIndex => $option) {
                    $oldOption = $oldAnswerOptions->get($index)->get($optionIndex);

                    $optionRevision = new Revision($oldOption);
                    $oldOption->answer_option = $newOptions[$optionIndex];

                    $oldOption->save();
                    $optionRevision->save($oldOption);
                }
            }

            $questionToModify->save();
            $questionRevision->save($questionToModify);
        }
        return Redirect::action('SurveyController@show', array('id' => $survey->id));
    }


    /*
     *  -------------------- END OF REST-CONTROLLER --------------------
     */

    /**
     * used to make URL's into hyperlinks using a <a> tag
     * @param string $text
     * @return string
     */
    private function addLinks($text)
    {
        $text = preg_replace('$(https?://[a-z0-9_./?=&#-]+)(?![^<>]*>)$i',
            ' <a href="$1" target="_blank">$1</a> ',
            $text);
        return preg_replace('$(www\.[a-z0-9_./?=&#-]+)(?![^<>]*>)$i',
            '<a target="_blank" href="http://$1"  target="_blank">$1</a> ',
            $text);
    }

    /**
     * used to get the type of a question
     * @param $value
     * @return null|string
     */
    private function getFieldTypeName($value)
    {
        switch ($value) {
            case 1:
                return "Freitext";
            case 2:
                return "Checkbox";
            case 3:
                return "Dropdown";
            case null:
                return null;
        }
        return "unbekannter Feldtyp";
    }

    /**
     * changes 0 to false and 1 to true
     * @param $boolean
     * @return null|string
     */
    private function booleanIntoText($boolean)
    {
        switch ($boolean) {
            case null:
                return null;
            case 0:
                return "Nein";
            case 1:
                return "Ja";
        }
        return "Fehler";
    }

}
