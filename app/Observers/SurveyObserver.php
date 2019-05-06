<?php

namespace Lara\Observers;

use Lara\Survey;
use Lara\utilities\CacheUtility;

class SurveyObserver
{
    /**
     * Handle the survey "created" event.
     *
     * @param  \Lara\Survey  $survey
     * @return void
     */
    public function created(Survey $survey)
    {
        CacheUtility::forgetMonthTableSurvey($survey);
    }

    /**
     * Handle the survey "updated" event.
     *
     * @param  \Lara\Survey  $survey
     * @return void
     */
    public function updated(Survey $survey)
    {
        CacheUtility::forgetMonthTableSurvey($survey);
    }

    /**
     * Handle the survey "deleted" event.
     *
     * @param  \Lara\Survey  $survey
     * @return void
     */
    public function deleted(Survey $survey)
    {
        CacheUtility::forgetMonthTableSurvey($survey);
    }

    /**
     * Handle the survey "restored" event.
     *
     * @param  \Lara\Survey  $survey
     * @return void
     */
    public function restored(Survey $survey)
    {
        //
    }

    /**
     * Handle the survey "force deleted" event.
     *
     * @param  \Lara\Survey  $survey
     * @return void
     */
    public function forceDeleted(Survey $survey)
    {
        //
    }
}
