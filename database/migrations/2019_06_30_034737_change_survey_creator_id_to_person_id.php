<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSurveyCreatorIdToPersonId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** prepare */
        DB::statement($this->createSurveyDeadlineBackup());

        /** migrate */
        DB::statement('SET foreign_key_checks = 0;');
        DB::statement($this->selectSurveyCreators());
        DB::statement($this->selectSurveyAnswerCreators());
        Schema::table('surveys', function (Blueprint $table) {
            $table->foreign('creator_id')->references('id')->on('persons');
        });
        DB::statement('SET foreign_key_checks = 1;');

        /** cleanup */
        DB::statement($this->restoreSurveyDeadline());
        DB::statement($this->dropSurveyDeadlineBackup());
    }

    private function createSurveyDeadlineBackup()
    {
        return <<<SQL
create table TMP_SURVEY as select id, deadline from surveys;
SQL;
    }

    private function restoreSurveyDeadline()
    {
        return <<<SQL
update surveys sur join TMP_SURVEY backup on sur.id = backup.id
set sur.deadline = backup.deadline;
SQL;
    }

    private function dropSurveyDeadlineBackup() {
        return <<<SQL
drop table TMP_SURVEY;
SQL;

    }

    private function selectSurveyCreators()
    {
        return <<<SQL
update surveys sur
    join (
        select s.id suvey_id, p.id person_id
        from surveys s
                 join persons p on p.prsn_ldap_id = s.creator_id
    ) vals
set sur.creator_id = vals.person_id
where ( sur.id = vals.suvey_id );
SQL;

    }

    private function selectSurveyAnswerCreators()
    {
        return <<<SQL
update survey_answers sur
    join (
        select s.id suvey_id, p.id person_id
        from survey_answers s
                 join persons p on p.prsn_ldap_id = s.creator_id
    ) vals
set sur.creator_id = vals.person_id
where ( sur.id = vals.suvey_id );
SQL;

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /** prepare */
        DB::statement($this->createSurveyDeadlineBackup());

        /** migrate */
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropForeign('creator_id');
        });
        DB::statement('SET foreign_key_checks = 0;');
        DB::statement($this->revertChanges());
        DB::statement($this->revertChangesAnswers());
        DB::statement('SET foreign_key_checks = 1;');

        /** cleanup*/
        DB::statement($this->restoreSurveyDeadline());
        DB::statement($this->dropSurveyDeadlineBackup());
    }

    private function revertChanges()
    {
        return <<<SQL
update surveys sur
    join (
        select s.id suvey_id, p.prsn_ldap_id prsn_ldap_id
        from surveys s
                 join persons p on p.id = s.creator_id
    ) vals
set sur.creator_id = vals.prsn_ldap_id
where ( sur.id = vals.suvey_id );
SQL;

    }

    private function revertChangesAnswers()
    {
        return <<<SQL
update survey_answers sur
    join (
        select s.id suvey_id, p.prsn_ldap_id prsn_ldap_id
        from survey_answers s
                 join persons p on p.id = s.creator_id
    ) vals
set sur.creator_id = vals.prsn_ldap_id
where ( sur.id = vals.suvey_id );
SQL;

    }
}
