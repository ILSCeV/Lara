<?php

use Illuminate\Database\Migrations\Migration;

class UserSectionsRoleView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement($this->createView());
    }
    
    private function createView()
    {
        return <<<SQL
create or replace view user_sections_role_view as
select u.id user_id, r.section_id
from users u join role_user ru on u.id = ru.user_id join roles r on ru.role_id = r.id
group by u.id, r.section_id;
SQL;
    
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->dropView());
    }
    
    private function dropView()
    {
        return <<<SQL
drop view if exists user_sections_role_view;
SQL;
    
    }
}
