<?php
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
/**
* Unit tests for EventController.
* Unit tests are fit for seeds.
*/
class EventControllerTest extends TestCase {

	public function testCallEventNewNoUser(){
		// call route "/event/new"
		$response = $this->call('GET', 'calendar/create');

        // Check that you get a 200 OK response.
        $this->assertTrue($response->isOk());
		
		// Check that no user has logged in.
		$this->assertContains('Zugriff verweigert', $response->getContent());
	}
	
	public function testCallEventNewUserIsOnlyMitglied(){
		$this->session(['userId' => '1003']);
		$this->session(['userGroup' => 'clubmitglied']);
		
		// call route "/event/new"
		$response = $this->call('GET', 'calendar/create');

        // Check that you get a 200 OK response.
        $this->assertTrue($response->isOk());
		
		// Check that user has no permission.
		$this->assertContains('Zugriff verweigert', $response->getContent());
	}
	
	public function testCallEventNewUserIsClubleitung(){
		$this->session(['userId' => '1001']);
		$this->session(['userGroup' => 'clubleitung']);
		
		// call route "/event/new"
		$response = $this->call('GET', 'calendar/create');

        // Check that you get a 200 OK response.
        $this->assertTrue($response->isOk());
		
		// Check that user has no permission.
		$this->assertNotContains('Zugriff verweigert', $response->getContent());
	}
	
	public function testCallEventNewUserIsMarketing(){
		$this->session(['userId' => '1002']);
		$this->session(['userGroup' => 'marketing']);
		
		// call route "/event/new"
		$response = $this->call('GET', 'calendar/create');

        // Check that you get a 200 OK response.
        $this->assertTrue($response->isOk());
		
		// Check that user has no permission.
		$this->assertNotContains('Zugriff verweigert', $response->getContent());
	}
	
	public function testCallEventNewSessionHasOnlyUserGroup(){
		$this->session(['userGroup' => 'clubleitung']);
		
		// call route "/event/new"
		$response = $this->call('GET', 'calendar/create');

        // Check that you get a 200 OK response.
        $this->assertTrue($response->isOk());
		
		// Check that user has no permission.
		$this->assertContains('Zugriff verweigert', $response->getContent());
	}
	
	public function testCallEventNewSessionHasOnlyUserId(){
		$this->session(['userId' => '1001']);
		
		// call route "/event/new"
		$response = $this->call('GET', 'calendar/create');

        // Check that you get a 200 OK response.
        $this->assertTrue($response->isOk());
		
		// Check that user has no permission.
		$this->assertContains('Zugriff verweigert', $response->getContent());
	}
	
	public function testCallEventEdit(){
		// call route "/event/edit"
		$response = $this->call('GET', 'calendar/id/1/edit');

        // Check that you get a 200 OK response.
        $this->assertTrue($response->isOk());

		// Check that no user has logged in.
		$this->assertContains('Zugriff verweigert', $response->getContent());
	}
	
	public function testCallEventEditUserIsOnlyMitglied(){
		$this->session(['userId' => '1003']);
		$this->session(['userGroup' => 'clubmitglied']);
		
		// call route "/event/edit"
		$response = $this->call('GET', 'calendar/id/1/edit');

        // Check that you get a 200 OK response.
        $this->assertTrue($response->isOk());
		
		// Check that user has no permission.
		$this->assertContains('Zugriff verweigert', $response->getContent());
	}
	
	public function testCallEventEditUserIsClubleitung(){
		$this->session(['userId' => '1001']);
		$this->session(['userGroup' => 'clubleitung']);
		
		// call route "/event/edit"
		$response = $this->call('GET', 'calendar/id/1/edit');

        // Check that you get a 200 OK response.
        $this->assertTrue($response->isOk());
		
		// Check that user has no permission.
		$this->assertNotContains('Zugriff verweigert', $response->getContent());
	}
	
	public function testCallEventEditUserIsMarketing(){
		$this->session(['userId' => '1002']);
		$this->session(['userGroup' => 'marketing']);
		
		// call route "/event/edit"
		$response = $this->call('GET', 'calendar/id/1/edit');

        // Check that you get a 200 OK response.
        $this->assertTrue($response->isOk());
		
		// Check that user has no permission.
		$this->assertNotContains('Zugriff verweigert', $response->getContent());
	}
	
	public function testCallEventEditSessionHasOnlyUserGroup(){
		$this->session(['userGroup' => 'clubleitung']);
		
		// call route "/event/edit"
		$response = $this->call('GET', 'calendar/id/1/edit');

        // Check that you get a 200 OK response.
        $this->assertTrue($response->isOk());
		
		// Check that user has no permission.
		$this->assertContains('Zugriff verweigert', $response->getContent());
	}
	
	public function testCallEventEditSessionHasOnlyUserId(){
		$this->session(['userId' => '1001']);
		
		// call route "/event/edit"
		$response = $this->call('GET', 'calendar/id/1/edit');

        // Check that you get a 200 OK response.
        $this->assertTrue($response->isOk());
		
		// Check that user has no permission.
		$this->assertContains('Zugriff verweigert', $response->getContent());
	}
	
}