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
    'notWorkingMail'        => 'Etwas funktioniert nicht? Schreibe :Name eine Mail',
    'mail'                  => ' eine Mail.', //not used
    //'moreInfos'           => 'Mehr Infos? Besuche die ',
    //'projectsite'         => 'Projektseite auf GitHub',
    'moreInfosProjectsite'  => 'Mehr Infos? Besuche die Projektseite auf GitHub',
    
    //-----------------------------------------------------------------------------------------------------

    // resources/views
    // resources/views/clubEventView.blade.php

    //Event types ------------------------------------
    'type'                  => 'Typ',
    'normalProgramm'        => 'normales Programm',
    'information'           => 'Information',
    'special'               => 'Spezial',
    'LiveBandDJ'            => 'Live Band / Live DJ / Lesung',
    'internalEvent'         => 'interne Veranstaltung',
    'utilization'           => 'Nutzung',
    'flooding'              => 'Fluten',
    'flyersPlacard'         => 'Flyer / Plakatieren',
    'marketingFlyersPlacard'=> 'Marketing / Flyer / Plakate', //used in legend.blade.php
    'preSale'               => 'Vorverkauf',
    'others'                => 'Sonstiges',
    
    //----------------------------------------------
    
    'begin'                 => 'Beginn',
    'end'                   => 'Ende',
    
    'DV-Time'               => 'DV-Zeit',
    'club'                  => 'Verein',
    'internalEventP'        => 'Interne Veranstaltung', // Placeholder string
    'internEventP'          => 'Internes Event',        // Placeholder string for example used in monthCell.blade.php
    
    'willShowFor'           => 'wird angezeigt für',
    
    'changeEvent'           => 'Veranstaltung ändern',
    'deleteEvent'           => 'Veranstaltung löschen',
    'confirmDeleteEvent'    => 'Diese Veranstaltung wirklich entfernen? Diese Aktion kann nicht rückgängig gemacht werden!',
    
    'additionalInfo'        => 'Weitere Details',
    'moreDetails'           => 'Interne Information',
    
    //Button
    'showMore'              => 'mehr anzeigen',
    'showLess'              => 'weniger anzeigen',
    
    'hideTimes'             => 'Zeiten ausblenden',
    
    'addComment'            => 'Kommentar hier hinzufügen',  //not used Line ClubEventView ~270 Placeholder message and similar
    
    //List of Changes
    'listChanges'           => 'Liste der Änderungen',

    'work'                  => 'Dienst',
    'whatChanged'           => 'Was wurde geändert?',
    'oldEntry'              => 'Alter Eintrag',
    'newEntry'              => 'Neuer Eintrag',
    'whoBlame'              => 'Wer ist schuld?',
    'whenWasIt'             => 'Wann war das?',

    //-------------------------------------------------------------------------------------------------------
    
    // resources/views/createClubEventView.blade.php
    'createNewVEvent'       => 'Neue Veranstaltung erstellen',
    'createNewEvent'        => 'Neues Event erstellen',
    'template'              => 'Vorlage',
    'templateNewSaveQ'      => 'Als neue Vorlage speichern?',
    'title'                 => 'Titel',
    'subTitle'              => 'Subtitel',
    'error'                 => 'Fehler',
    
    'showExtern'            => 'Für Externe sichtbar machen?',

    'survey'                => 'Umfrage',

    //blockString line~168
    'showForLoggedInMember'     => 'Dieses Event wird nur für eingeloggte Mitglieder sichtbar sein!',
    'showForExternOrChangeType' => 'Um sie für Externe sichtbar zu machen oder den Typ zu ändern,',
    'askTheCLOrMM'              => 'frage die Clubleitung oder die Marketingverantwortlichen.',

    'section'               => 'Sektion',
    'showFor'               => 'Zeige für',
    
    'passwordEntry'         => 'Passwort zum Eintragen',
    'passwordRepeat'        => 'Passwort wiederholen',
    'passwordDeleteMessage' => 'Um das Passwort zu löschen trage in beide Felder "delete" ein (ohne Anführungszeichen).',

    'moreInfos'             => 'Weitere Details',
    'public'                => 'öffentlich',
    'details'               => 'Interne Information',
    'showOnlyIntern'        => 'nur intern sichtbar',
    
    'backWithoutChange'     => 'Ohne Änderung zurück',
    
    //---------------------------------------------------------------------------------------------------------
    
    // resources/views/editClubEventView.blade.php
    'changeEventJob'        => 'Veranstaltung/Aufgabe ändern',
    
    //Lines for editing only with permission
    'noNotThisWay'          => 'Ne, das geht so nicht...',
    'onlyThe'               => 'Nur die',
    'clubManagement'        => 'Clubleitung',
    'orThe'                 => 'oder die',
    'marketingManager'      => 'Marketingverantwortlichen',
    'canChangeEventJob'     => 'dürfen diese Veranstaltung/Aufgabe ändern.',
    'commaThe'              => ', die ', //line number ~332
    
    //-----------------------------------------------------------------------------------------------------------
    
    // resources/views/createSurveyView.blade.php
    'createNewSurvey'       => 'Neue Umfrage erstellen',
    
    //-----------------------------------------------------------------------------------------------------------
    
    // resources/views/editSurveyView.blade.php
    'editSurvey'            => 'Umfrage editieren',
    'confirmDeleteSurvey'   => 'Möchtest du die Umfrage ":title" wirklich löschen?',
    
    //-----------------------------------------------------------------------------------------------------------
    
    // resources/views/surveyView.blade.php
    'description'           => 'Beschreibung',
    'surveyDeadlineTo'      => 'Die Umfrage läuft noch bis',
    'um'                    => 'um', //better translation needed 
    
    //result messages; can be changed with pluralization
    'noPersonAnswered'      => 'Es hat noch keine Person abgestimmt.',
    'onePersonAnswered'     => 'Es hat bereits eine Person abgestimmt.',
    'fewPersonAnswered1'    => 'Es haben bereits',
    'fewPersonAnswered2'    => 'Personen abgestimmt.',
    
    //tableau (head)
    'name'                  => 'Name',
    'myClub'                => 'Club',

    'addMe'                 => 'Mich eintragen!',
    
    //Answers
    'yes'                   => 'Ja',
    'no'                    => 'Nein',
    'noInformation'         => 'keine Angabe',
    
    'noClub'                => 'Extern',
    
    'confirmDeleteAnswer'   => 'Möchtest Du diese Antwort wirklich löschen?',
    
    //evaluation; can be changed with pluralization
    'evaluation'            => 'Auswertung',
    'personDidNotAnswer'    => 'Person wollte keine Angaben machen.',
    'personsDidNotAnswer'   => 'Personen wollten keine Angaben machen.',
    'personAnswered'        => 'Person stimmte für',
    'personsAnswered'       => 'Personen stimmten für',
    
    //List of Changes
    'who'                   => 'Wer',
    'summary'               => 'Zusammenfassung',
    'affectedColumn'        => 'Betroffene Spalte',
    'oldValue'              => 'Alter Wert',
    'newValue'              => 'Neuer Wert',
    'when'                  => 'Wann',
    
    //-----------------------------------------------------------------------------------------------------------
    
    // resources/views/monthView.blade.php
    //short weekdays + CW
    'Cw' => 'KW',
    'Mo' => 'Montag',
    'Tu' => 'Dienstag',
    'We' => 'Mittwoch',
    'Th' => 'Donnerstag',
    'Fr' => 'Freitag',
    'Sa' => 'Samstag',
    'Su' => 'Sonntag',


    // resources/view/partials/calendarLinkEvent.blade.php
    'addToCalendar' => 'Event zu eigenem Google Kalender hinzufügen',

    // resources/views/partials/month/day.blade.php
    'createEventOnThisDate' => 'Neues Event an diesem Tag erstellen',

    // resources/views/partials/month/monthCell.blade.php
    'showDetails' => 'Details anzeigen',

    // resources/views/monthView.blade.php
    'showWeek' => 'Detaillierte Ansicht dieser Woche anzeigen',
    
    //-----------------------------------------------------------------------------------------------------------
    
    // resources/views/createSurveyView.blade.php
    'noEventsThisWeek'  => 'Keine Veranstaltungen diese Woche',
    'noSurveysThisWeek' => 'Keine Umfragen diese Woche',
    
    //-----------------------------------------------------------------------------------------------------------
    
    // resources/views/listView.blade.php
    'for'               => 'Für',
    'noEventsPlanned'   => 'sind keine Veranstaltungen geplant',
    'noEventsOn'        => 'Keine Veranstaltungen am',
    'EventsFor'         => 'Veranstaltungen für',
    
    //-----------------------------------------------------------------------------------------------------------
    
    // resources/views/log.blade.php
    // not translated - international view
    
    //-----------------------------------------------------------------------------------------------------------
    
    // /resources/views/partials
    // /resources/views/partials/clubEventByIdSmall.blade.php
    'noResults'             => 'Keine Treffer',
    
    //-----------------------------------------------------------------------------------------------------------
    
    // /resources/views/partials/editSchedule.blade.php
    'adjustRoster'          => 'Dienstplan anpassen',
    'serviceTypeEnter'      => 'Diensttyp hier eingeben',
    'weight'                => 'Gewicht (für Statistik)',
    'statisticalEvaluation' => 'Statistik',
    
    //-----------------------------------------------------------------------------------------------------------
    
    // /resources/views/partials/filter.blade.php
    'allSections'           => 'Alle Sektionen',
    
    //-----------------------------------------------------------------------------------------------------------
    
    // /resources/views/partials/legend.blade.php
    //handled in the event type part in the /resources/views/clubEventView.blade.php part
    
    //-----------------------------------------------------------------------------------------------------------
    
    // /resources/views/partials/monthCell.blade.php
    'internalSurvey'        => 'Interne Umfrage',
    
    //-----------------------------------------------------------------------------------------------------------
    
    // /resources/views/partials/navigation.blade.php
    'month'                 => 'Monat',
    'week'                  => 'Woche',
    //not translated the term 'logs'
    'manageClub'            => 'Clubs verwalten',
    'manageJobType'         => 'Diensttypen verwalten', 
    // TODO use Job for Service - german: Dienst maybe change to Shift - Schicht
    
    'manageTemplate'        => 'Vorlagen verwalten',
    
    //create button text
    'createNewEvent'           => 'Neues Event erstellen',
    'createNewSurvey'          => 'Neue Umfrage erstellen',
    
    //Member types
    'candidate'             => 'Kandidat',
    'veteran'               => 'Veteran',
    'ex-member'             => 'ex-Mitglied',
    'active'                => 'Aktiv',
    'external'              => 'Extern',
    
    //-----------------------------------------------------------------------------------------------------------
    
    // /resources/views/partials/scheduleEntryName.blade.php
    'IDoIt'                 => 'Ich mache es!', //Ich mach's!
    
    // /resources/views/partials/scheduleEntryStatus.blade.php
    'jobFree'               => 'Dienst frei',

    // /resources/views/partials/statisticsLeaderboards.blade.php
    'totalShifts'           => 'Dienste',
    'leaderBoards'          => 'Bestenliste',
    'allClubs'              => 'Alle',

    // /resources/view/partials/statistics/amountOfShiftsDisplay.blade.php
    'shiftsInOtherSection'      => 'Dienste in anderen Sektionen',
    'shiftsInOwnSection'        => 'Dienste in eigener Sektion',

    // /resources/views/partials/clubStatistics.blade.php
    'infoFor'               => 'Mitgliederstatistik',

    // /resources/views/partials/personalStatistics.blade.php
    //-----------------------------------------------------------------------------------------------------------
    
    // /resources/views/partials/surveyAnswerStatus.blade.php
    //no new strings
    
    //-----------------------------------------------------------------------------------------------------------
    
    // /resources/views/partials/surveyForm.blade.php
    'showOnlyForLoggedInMember' => 'nur für eingeloggte Nutzer sichtbar',
    'showResultsOnlyForCreator' => 'Ergebnisse sind nur für den Umfragenersteller sichtbar',
    'showResultsAfterFillOut'   => 'Ergebnisse sind erst nach dem Ausfüllen sichtbar',
    
    'passwordSetOptional'       => 'Das Setzen eines Passworts ist optional',
    
    //Answer and Question options
    'answerOption'              => 'Antwortmöglichkeit',
    'question'                  => 'Frage',
    
    //Questionoptions
    'freeText'                  => 'Freitext',
    'checkbox'                  => 'Checkbox',
    'dropdown'                  => 'Dropdown',
    
    'required'                  => 'erforderlich',
    'addAnswerOption'           => 'Antwortmöglichkeit hinzufügen',
    'addQuestion'               => 'Frage hinzufügen',
    
    //-----------------------------------------------------------------------------------------------------------
    
    // /resources/views/partials/weekCellFull.blade.php
    'hide' => 'Ausblenden',
    
    //-----------------------------------------------------------------------------------------------------------
    
    // /resources/views/partials/weekCellHidden.blade.php
    'moreDetailsAfterLogInMessage'      => 'Weitere Details sind für Mitglieder nach dem Einloggen zugänglich.',
    // 'moreDetailsAfterLogInMessage2'  => 'nach dem Einloggen zugänglich.', 
    // Merged with line above but there is now way to break the line (format is still ok) 
    // ToDo find a solution

    //-----------------------------------------------------------------------------------------------------------
    
    // /resources/views/partials/weekCellProtected.blade.php
    //no new strings
    
    //-----------------------------------------------------------------------------------------------------------
    
    // /resources/views/partials/weekCellSurvey.blade.php
    //no new strings
    
    //-----------------------------------------------------------------------------------------------------------
    
    // /public/js ToDo add these strings
    // Maybe To do translate Javascript, tricky, " " can throw error messages
    // /public/js/vedst-script.js
    'errorMessageForgotFilter'              => 'Den Filter vergessen! Bitte setze mindestens eine Sektion, der diese Veranstaltung/Aufgabe gezeigt werden soll.',
    'errorMessageEnterPasswordForShiftPlan' => 'Bitte noch das Passwort für diesen Dienstplan eingeben:',
    'shiftTime'                             => 'Dienstzeiten',
    //'hideTimes' already exists
    
    'weekStart'                             => 'Montag - Sonntag',
    'hideTakenShifts'                       => 'Nur FREIe Dienste',
    
    //-----------------------------------------------------------------------------------------------------------
    
    //Controller  ToDo add these strings
    //ScheduleEntryController
    //action description
    'addedComment'              => 'Kommentar hinzugefügt',
    'deletedComment'            => 'Kommentar gelöscht',
    'changedComment'            => 'Kommentar geändert',

    'errorMessageAbortDeletion' => 'Fehler: Löschvorgang abgebrochen - der Dienstplaneintrag existiert nicht.',

    //SurveyController
    'messageSurveyDeleted'      => 'Umfrage gelöscht!',

    //SurveyAnswerController
    'messageSuccessfulVoted'    => 'Erfolgreich abgestimmt!',
    'messageSuccessfulDeleted'  => 'Erfolgreich gelöscht!',

    //-----------------------------------------------------------------------------------------------------------
    
    //placeholder strings (e.g. used in buttons or text fields)
    
    /*
    | instead of { trans('message.key' }} use  Lang::get('message.key') in the view
    */
    
    //ClubEvent
    'addCommentHere'                => 'Kommentar hier hinzufügen',
    'enterPasswordHere'             => 'Passwort hier eingeben',
    'placeholderTitleWineEvening'   => 'z.B. Weinabend', 
    //'placeholderTitleWineEvening'
    
    'placeholderSubTitleWineEvening'=> 'z.B. Das Leben ist zu kurz, um schlechten Wein zu trinken', 
    //'placeholderSubTitleWineEvening'
    
    'placeholderPublicInfo'         => 'z.B. Karten nur im Vorverkauf',
    'placeholderPrivateDetails'     => 'z.B. DJ-Tisch wird gebraucht',
    
    //Survey
    'addAnswerHere'                 => 'Antwort hier hinzufügen',
    'createSurvey'                  => 'Umfrage erstellen', //Button text
    
    //Partials
    //Navigation
    'clubNumber'                    => 'Clubnummer',
    'password'                      => 'Passwort',
    'logIn'                         => 'Anmelden',
    'logOut'                        => 'Abmelden',
    //ScheduleEntryName
    '=FREI='                        => '=FREI=', //not used yet
    //surveyForm
    'placeholderSurveyTitle'        => 'Titel:',
    'placeholderTitleSurvey'        => 'z.B. Teilnahme an der Clubfahrt',
    'placeholderDescription'        => 'Beschreibung:',
    'placeholderActiveUntil'        => 'Aktiv bis:',
    
    // Misc.
    'guest'                         => 'Gast',
    'accessDenied'                  => 'Moment mal... Nur eingeloggte Mitglieder mit ausreichender Berechtigung dürfen hier rein! Logge dich ein, oder komm zur nächsten Versammlung und werde Mitglied.',



    ////////////////
    // Management //
    ////////////////

    'management'                    => 'Verwaltung',
    'jobType'                       => 'Diensttyp',
    'jobtypes'                      => 'Diensttypen',
    'shift'                         => 'Dienst',
    'start'                         => 'Beginn',
    'end'                           => 'Ende',
    'weight'                        => 'Wert',
    'actions'                       => 'Aktionen',
    'deleteConfirmation'            => 'Möchtest du folgenden Diensttyp wirklich löschen:',
    'warningNotReversible'          => 'Diese Aktion kann man nicht rückgängig machen!',
    'editDetails'                   => 'Details anpassen',
    'deleteThisJobtype'             => 'Entfernen',

    'reset'                         => 'Zurücksetzen',
    'update'                        => 'Änderungen speichern',
    'delete'                        => 'löschen',
    'jobtypeNeverUsed'              => 'Dieser Diensttyp wird bei keinem einzigen Event benutzt... Traurig, so was... Vielleicht wäre es sinnvoll, ihn einfach zu',
    'jobtypeUsedInFollowingEvents'  => 'Dieser Dienstyp wird bei folgenden Events eingesetzt. Um ihn zu entfernen, ersetze jede Instanz erst mit einem anderen Diensttyp.',
    'event'                         => 'Event',
    'date'                          => 'Datum',
    'substituteThisInstance'        => 'Ersetzen durch...',

    'cantTouchThis'                 => 'Netter Versuch - du darfst das nicht einfach ändern! Frage die Clubleitung oder Markleting ;)',
    'cantBeBlank'                   => 'Diese Werte dürfen nicht leer sein.',
    'nonNumericStats'               => 'Statistische Wertung muss man mit Ziffern eingeben ;)',
    'negativeStats'                 => 'Statistische Wertung darf nicht negativ sein.',
    'changesSaved'                  => 'Änderungen erfolgreich gespeichert.',
    'deleteFailedJobtypeInUse'      => 'Diensttyp wurde NICHT gelöscht, weil er noch im Einsatz ist. Hier kannst du es ändern.',

    //////////
    // ICal //
    //////////
    'icalfeeds'                     => 'Kalenderfeed im ICal-Format',
];