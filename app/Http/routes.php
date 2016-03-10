<?php

/* 
|--------------------------------------------------------------------------
| Default Laravel routes for reference
|--------------------------------------------------------------------------
| 
| Route::get('/', 'WelcomeController@index');
| 
| Route::get('home', 'HomeController@index');
| 
| Route::controllers([
| 	'auth' => 'Auth\AuthController',
| 	'password' => 'Auth\PasswordController',
| 	]);
| 
*/


/*
|--------------------------------------------------------------------------
| Global Patterns
|--------------------------------------------------------------------------
*/ 
Route::pattern('id', 	'[0-9]+');
Route::pattern('year', 	'[2][0][0-9][0-9]');
Route::pattern('month',	'[0][1-9]|[1][0-2]');
Route::pattern('week', 	'[0-5][0-9]');
Route::pattern('day', 	'[0-3][0-9]');


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

// LOG VIEWER
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


// DEFAULT
Route::get('/', 								'MonthController@currentMonth');
Route::get('/calendar/',						'MonthController@currentMonth');


// AUTHENTIFICATION
Route::get('login',								'MonthController@currentMonth');
Route::get('logout', 							'LoginController@doLogout');

Route::post('login', 							'LoginController@doLogin');
Route::post('logout', 							'LoginController@doLogout');


// TIMESTAMP
Route::get('updates/{id}', 						'ScheduleController@getUpdates');


// YEAR
Route::get('/calendar/year',					'YearController@currentYear');
Route::get('/calendar/{year}',					'YearController@showYear');


// MONTH
Route::get('/calendar/month',					'MonthController@currentMonth');
Route::get('/calendar/{year}/{month}',			'MonthController@showMonth');


// WEEK
Route::get('/calendar/week/{id}', 				'WeekController@showId');
Route::get('/calendar/week', 					'WeekController@currentWeek');
Route::get('/calendar/{year}/KW{week}', 		'WeekController@showWeek');


// DATE
Route::get('/calendar/today',					'DateController@currentDate');
Route::get('/calendar/{year}/{month}/{day}',	'DateController@showDate');


// CREATE
Route::get('event/{year?}/{month?}/{day?}/{templateId?}/create', 'ClubEventController@create');

/*

Route::get('/task/create',						'TaskController@showCreateTask');

Route::post('/task/create', 					['as'   => 'newTask',
												 'uses' => 'TaskController@createTask']);

*/

// RESTful RESOURCES
Route::resource('entry', 	'ScheduleEntryController', 	['except' => ['index', 'create', 'store', 'edit', 'destroy']]);
Route::resource('schedule', 'ScheduleController', 		['except' => ['index', 'create', 'store', 'edit', 'destroy']]);
Route::resource('event', 	'ClubEventController', 		['except' => ['index']]);


// EVENT ID
// Route::get('/calendar/id/{id}', 				'CalendarController@showId');
// Route::get('/calendar/id/{id}/edit',			'EventController@showEditEvent');
// Route::get('/calendar/id/{id}/delete',			'EventController@deleteEvent');

// Route::post('/calendar/id/{id}/edit', 			['as'   => 'editClubEvent', 
//												 'uses' => 'EventController@editEvent']);


// SCHEDULE
// Route::get('/schedule/',						'ScheduleController@showScheduleList');
// Route::get('/schedule/id/{id}',					'ScheduleController@showSchedule');
// Route::post('/schedule/id/{id}',				'ScheduleController@updateSchedule');


// TASK
// Route::get('/task',								'ScheduleController@showTaskList');								
// Route::get('/task/id/{id}',						'ScheduleController@showSchedule');
// Route::get('/task/id/{id}/edit',				'TaskController@showEditTask');
// Route::get('/task/id/{id}/delete',				'TaskController@deleteTask');

// Route::post('/task/id/{id}',					'ScheduleController@updateSchedule');
// Route::post('/task/id/{id}/edit', 				['as'   => 'editTask',
// 												 'uses' => 'TaskController@editTask']);



/*
// STATISTICS
Route::get('/statistics',						'StatisticsController@showStatistics');

Route::post('/statistics', 						['as'   => 'statisticsChangeDate',
											     'uses' => 'StatisticsController@showStatistics']);



// MANAGEMENT
Route::get('/management/jobtypes',				'ManagementController@showJobTypes');
Route::get('/management/places',				'ManagementController@showPlaces');
Route::get('/management/templates',				'ManagementController@showTemplates');

Route::post('/management/jobtypes', 			['as'   => 'updateJobTypes',
												 'uses' => 'ManagementController@updateJobTypes']);
Route::post('/management/places', 				['as'   => 'updatePlaces',
												 'uses' => 'ManagementController@updatePlaces']);
Route::post('/management/templates', 			['as'   => 'updateTemplates',
												 'uses' => 'ManagementController@updateTemplates']);



/*
|--------------------------------------------------------------------------
| Data
|--------------------------------------------------------------------------
*/

// TYPEAHEAD PERSON NAMES for bc-Club (club '2')
Route::get('/data/persons-club-2.json', function() {
    // Cache results for 10 minutes for a small performance boost because users don't change very often
	$persons = Cache::remember('persons-club-2', 10 , function()
		{
			return  \Lara\Person::whereNotNull( "prsn_ldap_id" )
								// Get all active members from club 2
								->where( "clb_id", "=", "2" )
								->whereIn( "prsn_status", ['member', 'candidate'] )
								->orWhere(function($query)
								{
									// And add to the list any retired members only if they made a shift in the last 3 month
									$timeSpan = new DateTime("now");
									$timeSpan = $timeSpan->sub(DateInterval::createFromDateString('3 months'));

									$query->whereNotNull( "prsn_ldap_id" )
										  ->where( "clb_id", "=", "2" )
										  ->where( "updated_at", ">=", $timeSpan->format('Y-m-d H:i:s') );	
								})
							   	->orderBy('prsn_name')
							   	->get();
		});
       
    return Response::json($persons);
});

// TYPEAHEAD PERSON NAMES for bc-Café (club '3')
Route::get('/data/persons-club-3.json', function() {
    // Cache results for 10 minutes for a small performance boost because users don't change very often
	$persons = Cache::remember('persons-club-3', 10 , function()
		{
			return  \Lara\Person::whereNotNull( "prsn_ldap_id" )
								// Get all active members from club 3
								->where( "clb_id", "=", "3" )
								->whereIn( "prsn_status", ['member', 'candidate'] )
								->orWhere(function($query)
								{
									// And add to the list any retired members only if they made a shift in the last 3 month
									$timeSpan = new DateTime("now");
									$timeSpan = $timeSpan->sub(DateInterval::createFromDateString('3 months'));

									$query->whereNotNull( "prsn_ldap_id" )
										  ->where( "clb_id", "=", "3" )
										  ->where( "updated_at", ">=", $timeSpan->format('Y-m-d H:i:s') );	
								})
							   	->orderBy('prsn_name')
							   	->get();
		});

    return Response::json($persons);
});
      