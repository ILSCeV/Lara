<?php

namespace Lara\Library;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Lara\RevisionEntry;
use Illuminate\Support\Facades\Session;
use Lara\RevisionObjectRelation;


/**
 * Class Revision
 *
 * This class is designed to make generating revisions as easy as possible.
 * To store the data we need 2 tables, "revisions" and "revision_entries" -> look up the migrations for details.
 *
 * Useage:
 * You have to load the model before applying changes into a new instance of this class, after applying your
 * changes you call the function "save(...)" with the changed model as paramater and the class will generate
 * corresponding database entries.
 *
 * example:
 *          $answer = SurveyAnswer::findOrFail($id);
 *          $revision = new Revision($answer);
 *          $answer->answer = "Änderung";
 *          $answer->save();
 *          $revision->save($answer);
 * This example will generate an entry in "revisions" with the user data (username, ip, timestamp) and
 * an entry in "revision_entries" in which the old and new value of the Model are stored.
 *
 *
 * ToDo: ignore every column with "id" in it by default
 *
 *
 * @package Lara\Library
 */
class Revision
{
    /**
     * @var Model
     */
    private $old_model;

    /**
     * table columns which should not be part of the revisions, for example id's and timestamps
     * @var string[]
     */
    private $ignoreArray = ["created_at", "updated_at", "deleted_at", "survey_question_id", "survey_answer_id", "survey_question_id", "survey_id", "creator_id", "id", "order"];

    /**
     * Revision constructor.
     * @param Model $old_model
     */
    public function __construct($old_model)
    {
        $this->old_model = clone $old_model;
    }


    /**
     * @param Model $new_model
     * @param string $summary
     * @return bool
     */
    public function save(Model $new_model, $summary = null)
    {
        if($new_model->getTable() !== $this->old_model->getTable()) {
            // old and new model dont have the same class -> they are not compareable
            return false;
        }
        if($new_model == $this->old_model) {
            // no changes -> no entry
            return false;
        }
        
        $personId = Auth::check() ? Auth::user()->person->prsn_ldap_id : null;
        // workaround for getting all revisions created in this request
        // determine if there was a recent related Revision created
        $revision = \Lara\Revision::where( "created_at", ">", Carbon::now()->subSecond(2) )
                                  ->where( "creator_id", "=", $personId )
                                  ->where( 'request_uri', "=", request()->getUri() )
                                  ->first();
        if(empty($revision)){
            // otherwise create a new one
            $revision = new \Lara\Revision();
        }

        $revision->creator_id = $personId;
        $revision->ip = request()->ip();
        $revision->request_uri = request()->getUri();
        
        // if there is a summary specified a summary-entry will be generated
        if(!empty($summary)) {
                $revision->summary = $summary;
        }

        $revision->save();

        $revision_object_relation = new RevisionObjectRelation();
        $revision_object_relation->revision_id = $revision->id;
        $revision_object_relation->object_id = $new_model->id;
        $revision_object_relation->object_name = $this->parse_classname(get_class($new_model))['classname'];
        $revision_object_relation->save();

        if ($new_model->wasRecentlyCreated) {     // empty($this->old_model->attributesToArray())
            // new entry
            foreach($new_model->attributesToArray() as $column_name => $column_value) {
                $this->save_revision_entry($column_name, $column_value, $revision->id, "create");
            }
        } elseif (!$new_model->exists) {
            // deleted entry
            foreach($this->old_model->attributesToArray() as $column_name => $column_value) {
                $this->save_revision_entry($column_name, $column_value, $revision->id, "delete");
            }
        } else {
            // update entry
            foreach($new_model->attributesToArray() as $column_name => $column_value) {
                if($column_value != $this->old_model->attributesToArray()[$column_name]) {
                    $this->save_revision_entry($column_name, $column_value, $revision->id, "update");
                }
            }
        }
        return true;
    }

    /**
     * @param string $name
     * @return array
     */
    protected function parse_classname ($name)
    {
        return array(
            'namespace' => array_slice(explode('\\', $name), 0, -1),
            'classname' => join('', array_slice(explode('\\', $name), -1)),
        );
    }

    /**
     * @param string $column_name
     * @param string $column_value
     * @param int $revision_id
     * @param string $type "create" | "update" | "delete"
     * @return bool
     */
    protected function save_revision_entry($column_name, $column_value, $revision_id, $type)
    {
        if (in_array($column_name, $this->ignoreArray)) {
            // filter columns which should not be shown in revisions
            return false;
        }
        if ($type != "create" && $type != "update" && $type != "delete") {
            // type needs to be one of those 3 options
            return false;
        }

        $revision_entry = new RevisionEntry();
        $revision_entry->revision_id = $revision_id;
        $revision_entry->changed_column_name = $column_name;
        switch ($type) {
            case "create":
                $revision_entry->new_value = $column_value;
                break;
            case "delete":
                $revision_entry->old_value = $column_value;
                break;
            case "update":
                $revision_entry->new_value = $column_value;
                $revision_entry->old_value = $this->old_model->attributesToArray()[$column_name];
        }
        return $revision_entry->save();
    }

    /**
     * Deletes the model and creates a Revision
     * @param $model the Model to delete
     * @return bool true if saving the revision was successfull
     */
    public static function deleteWithRevision(Model $model)
    {
        $revision = new Revision($model);
        return $model->delete() && $revision->save($model, 'Deleted');
    }
}
