<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Language Lines
    |--------------------------------------------------------------------------
    |
    | Main language lines sorted by files
    | Lines are used multiple times, when they are similar in different views.
    | That means that new lines in this file only appear, when they appeared
    | for the first time while locating the files.
    |
    | -Structure,etc. can be changed, but remember to change all trans() and strings accordingly
    |
    | You want to add a new language? Steps:
    | in config/language.php add it in the following form: 'de' => 'Deutsch',
    | in lara/resources/lang add a new folder + file in this style
    |
    |
    */

    // /app
    // /resources/views
    // /resources/views/errors
    //-----------------------------------------------------------------------------------------------------
    // /resources/views/layouts/master.blade.php
    'notWorkingMail' => 'Something is not working? Write :Name a mail.',
    'mail'     => ' a Mail.', //not used
    //'moreInfos' => 'More informations? Visit the ',
    //'projectsite' => 'projectsite on GitHub',
    'moreInfosProjectsite' => 'More informations? Visit the projectsite on GitHub',

    //-----------------------------------------------------------------------------------------------------
    // resources/views
    // resources/views/clubEventView.blade.php
    //Event types ------------------------------------
    'type' => 'Type',
    'normalProgramm' => 'Normal Programm',
    'information' => 'Information',
    'special' => 'Special',
    'LiveBandDJ' => 'Live Band / Live DJ / Lesson',
    'internalEvent' => 'Internal Event',
    'utilization' => 'Utilization',
    'flooding' => 'Flooding',
    'flyersPlacard' => 'Flyer / Placard',
    'marketingFlyersPlacard' => 'Marketing / Flyer / Posters', //used in legend.blade.php
    'preSale' => 'Pre Sale',
    'others' => 'Others',
    //----------------------------------------------
    'begin' => 'Start',
    'end' => 'End',

    'DV-Time' => 'DV-Time',
    'club' => 'Club',
    'internalEventP' => 'Internal Event', // Placeholder string
    'internEventP' => 'Internal Event', //Placeholder string for example used in monthCell.blade.php
    //
    'willShowFor' => 'will be showed for',
    //
    'changeEvent' => 'Change Event',
    'deleteEvent' => 'Delete Event',
    'confirmDeleteEvent' => 'Really remove this event? This action can not be undone!',
    //
    'additionalInfo' => 'Additional Info',
    'moreDetails' => 'More Details',
    //Button
    'showMore' => 'show more',
    'showLess' => 'show less',
    //
    'hideTimes' => 'Hide Times',
    //
    'addComment' => 'add comment here',  //not used Line ClubEventView ~270 Placeholder message and similar
    //List of Changes
    'listChanges' => 'List of changes',

    'work' => 'Shift', //Dienst
    'whatChanged' => 'What was changed?',
    'oldEntry' => 'Old entry',
    'newEntry' => 'New entry',
    'whoBlame' => 'Who is to blame?',
    'whenWasIt' => 'When was it?',

    //-------------------------------------------------------------------------------------------------------
    // resources/views/createClubEventView.blade.php
    'createNewVEvent' => 'Create new Event', //Veranstaltung
    'createNewEvent' => 'Create new Event', //Event
    'template' => 'Template',
    'templateNewSaveQ' => 'Save as new template?',
    'title' => 'Title',
    'subTitle' => 'Subtitle',

    'showExtern' => 'Show for external?',

    'survey' => 'Survey',

    //blockString line~168
    'showForLoggedInMember' => 'This opening will only be visible to logged in members!',
    'showForExternOrChangeType' => 'To make them visible for External or to change the type,',
    'askTheCLOrMM' => 'ask the club management or marketing managers.',

    'section' => 'Section',
    'showFor' => 'Show for',
    //password
    'passwordEntry' => 'Password to enter',
    'passwordRepeat' => 'Confirm Password',
    'passwordDeleteMessage' => 'To delete the password, enter "delete" (without quotation marks) in both fields.',

    'moreInfos' => 'More Infos',
    'public' => 'public',
    'details' => 'Details',
    'showOnlyIntern' => 'only visible internally',

    'backWithoutChange' => 'Back without change',
    //---------------------------------------------------------------------------------------------------------
    // resources/views/editClubEventView.blade.php
    'changeEventJob' => 'Change Event/Task',
    //Lines for editing only with permission
    'noNotThisWay' => 'No, not this way...',
    'onlyThe' => 'Only the',
    'clubManagement' => 'Club Management',
    'orThe' => 'or the',
    'marketingManager' => 'Marketing Manager',
    'canChangeEventJob' => 'may change this event / task.',

    'only' => 'Only',
    'commaThe' => ', the ', //line number ~332
    //-----------------------------------------------------------------------------------------------------------
    // resources/views/createSurveyView.blade.php
    'createNewSurvey' => 'Create new Survey',
    //-----------------------------------------------------------------------------------------------------------
    // resources/views/editSurveyView.blade.php
    'editSurvey' => 'Edit Survey',
    'confirmDeleteSurvey1' => 'Are you sure you want the survey ', //placeholder variable after this
    'confirmDeleteSurvey2' => ' deleted?',
    //-----------------------------------------------------------------------------------------------------------
    // resources/views/surveyView.blade.php
    'description' => 'Description',
    'surveyDeadlineTo' => 'The Survey runs until',
    'um' => 'at', //better translation needed TODO change key to "at" and change trans() in views
    //result messages; can be changed with pluralization
    'noPersonAnswered' => 'No person has voted yet',
    'onePersonAnswered' => 'One person already answered',
    'fewPersonAnswered1' => 'There have already',
    'fewPersonAnswered2' => 'persons answered.',
    //tableau (head)
    'name' => 'Name',
    'myClub' => 'My Club',

    'addMe' => 'Add Me!',
    //Answers
    'yes' => 'Yes',
    'no' => 'No',
    'noInformation' => 'No information given',

    'noClub' => 'no Club',

    'confirmDeleteAnswer' => 'Do you really want to delete this answer?',
    //evaluation; can be changed with pluralization
    'evaluation' => 'Evaluation',
    'personDidNotAnswer' => 'Person did not want to make a specification.',
    'personsDidNotAnswer' => 'Persons did not want to make specifications.',
    'personAnswered' => 'Person voted for',
    'personsAnswered' => 'Persons voted for',
    //List of Changes
    'who' => 'Who',
    'summary' => 'Summary',
    'affectedColumn' => 'Affected Column',
    'oldValue' => 'Old Value',
    'newValue' => 'New Value',
    'when' => 'When',
    //-----------------------------------------------------------------------------------------------------------
    // resources/views/monthView.blade.php
    //short weekdays + CW
    'Cw' => 'KW',
    'Mo' => 'Mo',
    'Tu' => 'Tu',
    'We' => 'We',
    'Th' => 'Th',
    'Fr' => 'Fr',
    'Sa' => 'Sa',
    'Su' => 'Su',
    //-----------------------------------------------------------------------------------------------------------
    // resources/views/createSurveyView.blade.php
    'noEventsThisWeek' => 'No Events this week',
    'noSurveysThisWeek' => 'No Surveys this week',
    //-----------------------------------------------------------------------------------------------------------
    // resources/views/listView.blade.php
    'for' => 'for',
    'noEventsPlanned' => 'no Events are planned',
    'noEventsOn' => 'No Events on',
    'EventsFor' => 'Events for',
    //-----------------------------------------------------------------------------------------------------------
    // resources/views/log.blade.php
    // not translated - international view
    //-----------------------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------
    // /resources/views/partials
    // /resources/views/partials/clubEventByIdSmall.blade.php
    'noResults' => 'No results',
    //-----------------------------------------------------------------------------------------------------------
    // /resources/views/partials/editSchedule.blade.php
    'adjustRoster' => 'Adjust Roster',
    'serviceTypeEnter' => 'Enter Shifttype here',
    'weight' => 'Weighting',
    'statisticalEvaluation' => 'Statistical Evaluation',
    //-----------------------------------------------------------------------------------------------------------
    // /resources/views/partials/filter.blade.php
    'allSections' => 'All sections',
    //-----------------------------------------------------------------------------------------------------------
    // /resources/views/partials/legend.blade.php
    //handled in the event type part in the /resources/views/clubEventView.blade.php part
    //-----------------------------------------------------------------------------------------------------------
    // /resources/views/partials/monthCell.blade.php
    'internalSurvey' => 'Internal Survey',
    //-----------------------------------------------------------------------------------------------------------
    // /resources/views/partials/navigation.blade.php
    'month' => 'Month',
    'week' => 'Week',
    //not translated the term 'logs'
    'manageClub' => 'Manage Clubs',
    'manageJobType' => 'Manage Shifttypes', // TODO use Job for Service - german: Dienst maybe change to Shift - Schicht
    'manageTemplate' => 'Manage Templates',
    //create button text
    'createAndAddNewEvent' => 'Add new Event/Task',
    'createAndAddNewSurvey' => 'Add new Survey',
    //Member types
    'candidate' => 'Candidate',
    'veteran' => 'Veteran',
    'ex-member' => 'ex-Member',
    'active' => 'Active',
    'external' => 'External',
    //-----------------------------------------------------------------------------------------------------------
    // /resources/views/partials/scheduleEntryName.blade.php
    'IDoIt' => 'I do it!', //Ich mach's!
    // /resources/views/partials/scheduleEntryStatus.blade.php
    'jobFree' => 'Shift free',
    //-----------------------------------------------------------------------------------------------------------
    // /resources/views/partials/surveyAnswerStatus.blade.php
    //no new strings
    //-----------------------------------------------------------------------------------------------------------
    // /resources/views/partials/surveyForm.blade.php
    'showOnlyForLoggedInMember' => 'Only visible to logged in users',
    'showResultsOnlyForCreator' => 'Results are only visible to the survey creator',
    'showResultsAfterFillOut' => 'Results are visible after filling out the survey',

    'passwordSetOptional' => 'Setting a password is optional',
    //Answer and Question options
    'answerOption' => 'Answer option',
    'question' => 'Question',
    //Questionoptions
    'freeText' => 'Free Text', //TODO Line ~299 Freitext throws buggy string with trans(), maybe because of Javascript
    'checkbox' => 'Checkbox',
    'dropdown' => 'Dropdown',

    'required' => 'required',
    'addAnswerOption' => 'Add Answer option',
    'addQuestion' => 'Add Question',
    //-----------------------------------------------------------------------------------------------------------
    // /resources/views/partials/weekCellFull.blade.php
    'hide' => 'Hide',
    //-----------------------------------------------------------------------------------------------------------
    // /resources/views/partials/weekCellHidden.blade.php
    'moreDetailsAfterLogInMessage1' => 'More details are for members',
    'moreDetailsAfterLogInMessage2' => 'after logging in.',
    //-----------------------------------------------------------------------------------------------------------
    // /resources/views/partials/weekCellProtected.blade.php
    //no new strings
    //-----------------------------------------------------------------------------------------------------------
    // /resources/views/partials/weekCellSurvey.blade.php
    //no new strings
    //-----------------------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------
    // /public/js ToDo add these strings
    //Maybe To do translate Javascript, tricky, " " can throw error messages
    // /public/js/vedst-script.js
    'errorMessageForgotFilter' => 'Den Filter vergessen! Bitte setze mindestens eine Sektion, der diese Veranstaltung/Aufgabe gezeigt werden soll.',
    'errorMessageEnterPasswordForShiftPlan' => 'Bitte noch das Passwort für diesen Dienstplan eingeben:',
    'showTimes' => 'show times',
    //'hideTimes' already exists
    'weekMoSu' => 'Week: Monday - Sunday',
    'weekWeTu' => 'Week: Wednesday - Tuesday',
    //-----------------------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------
    //Controller  ToDo add these strings
    //ScheduleEntryController
    //action description
    'addedComment'=> 'Kommentar hinzugefügt',
    'deletedComment' => 'Kommentar gelöscht',
    'changedComment' => 'Kommentar geändert',

    'errorMessageAbortDeletion' => 'Fehler: Löschvorgang abgebrochen - der Dienstplaneintrag existiert nicht.',

    //SurveyController
    'messageSurveyDeleted' => 'Umfrage gelöscht!',

    //SurveyAnswerController
    'messageSuccessfulVoted' => 'Erfolgreich abgestimmt!',
    'messageSuccessfulDeleted' => 'Erfolgreich gelöscht!',

    //-----------------------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------
    //placeholder strings (e.g. used in buttons or text fields)
    /*
    | instead of { trans('message.key' }} use  Lang::get('message.key') in the view
    */
    //ClubEvent
    'addCommentHere' => 'add comment here',
    'enterPasswordHere' => 'enter password here',
    'placeholderTitleWineEvening' => 'e.g. Wine evening', //'placeholderTitleWineEvening'
    'placeholderSubTitleWineEvening' => 'e.g. Life is too short to drink bad wine', //'placeholderSubTitleWineEvening'
    'placeholderPublicInfo'=> 'e.g. Tickets only in presale',
    'placeholderPrivateDetails' => 'e.g. DJ-table is needed',
    //Survey
    'addAnswerHere' => 'add answer here',
    'createSurvey' => 'create survey', //Button text
    //Partials
    //Navigation
    'clubNumber' => 'Clubnumber',
    'password' => 'Password',
    'logIn' => 'Login',
    'logOut' => 'Logout',
    //ScheduleEntryName
    '=FREI=' => '=FREI=', //not used yet
    //surveyForm
    'placeholderSurveyTitle' => 'Title:',
    'placeholderTitleSurvey' => 'e.g. Participation in the Club trip',
    'placeholderDescription' => 'Description:',
    'placeholderActiveUntil' => 'Active until:',

    //
];