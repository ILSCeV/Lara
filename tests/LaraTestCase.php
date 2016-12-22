<?php

class LaraTestCase extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    function actingAsLara(Lara\Person $person, $userGroup = 'bc-Club', $userClub = 'bc-Club', $userStatus = 'member') {
        Session::put('userId', $person->prsn_ldap_id);
        Session::put('userName', $person->prsn_name);
        Session::put('userGroup', $userGroup);
        Session::put('userClub', $userClub);
        Session::put('userStatus', $userStatus);
        return $this;
    }
    
    function seePageIsNot($uri) {
        $this->assertPageLoaded($uri = $this->prepareUrlForRequest($uri));

        $this->assertNotEquals(
            $uri, $this->currentUri, "Did land on unexpected page [{$uri}].\n"
        );

        return $this;
    }
}