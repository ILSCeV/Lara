<?php
use Lara\Survey;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SurveyControllerTest extends TestCase
{
    // using DatabaseTransactions, so tests do not affect our DB
    use DatabaseTransactions;
    /** @test */
    function can_fake()
    {
        $faked = factory(Survey::class)->create();
    }

    /** @test */
    function can_get_person_that_created_survey()
    {
        $person = factory(\Lara\Person::class)->create();
        $faked = factory(Survey::class)->create(['creator_id' => $person->prsn_ldap_id]);
        $this->assertEquals($person->prsn_ldap_id, $faked->getPerson->prsn_ldap_id);
    }
}
