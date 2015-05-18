<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/* Laravel default routes for reference

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
	]);

*/

/* Route::get('/', function()
	{
		return View::make('betatest-index');
	});
*/

Route::get('/', 'CalendarController@currentMonth');

/** Global Patterns
 *
 * Route::pattern('id', '[0-9]+'); //only once
 *
 * Route::get('/aaa/bbb/{id}', '...');
 * Route::get('/ccc/ddd/{id}', '...');
 *
 * instead of
 *
 * Route::get('/aaa/bbb/{id}', '...')->where('id', '[0-9]+');"
 * Route::get('/ccc/ddd/{id}', '...')->where('id', '[0-9]+');" 
 * 
 * More about: http://laravel.com/docs/4.2/routing#route-parameters
 *
 * -RU
 *
**/ 
Route::pattern('id', '[0-9]+');
Route::pattern('year', '[2][0][0-9][0-9]');
Route::pattern('month', '[0][1-9]|[1][0-2]');
Route::pattern('week', '[0-5][0-9]');
Route::pattern('day', '[0-3][0-9]');


// DEFAULT
Route::get('/calendar/',						'CalendarController@currentMonth');


// YEAR
Route::get('/calendar/year',					'CalendarController@currentYear');

Route::get('/calendar/{year}',					'CalendarController@showYear');


// MONTH
Route::get('/calendar/month',					'CalendarController@currentMonth');

Route::get('/calendar/{year}/{month}',			'MonthController@showMonth');


// WEEK
Route::get('/calendar/week/{id}', 				'WeekController@showId');

Route::get('/calendar/week', 					'WeekController@currentWeek');

Route::get('/calendar/{year}/KW{week}', 		'WeekController@showWeek');

Route::post('/calendar/{year}/KW{week}',		['as' => 'bulkUpdate', 
													  'uses' => 'ScheduleController@bulkUpdateSchedule']);


// DAY
Route::get('/calendar/today',					'CalendarController@currentDay');

Route::get('/calendar/{year}/{month}/{day}',	'CalendarController@showDate');


// EVENT ID
Route::get('/calendar/id/{id}', 				'CalendarController@showId');

Route::get('/calendar/id/{id}/edit',			'EventController@showEditEvent');

Route::post('/calendar/id/{id}/edit', 			array('as' => 'editClubEvent', 
													  'uses' => 'EventController@editEvent'));

Route::get('/calendar/id/{id}/delete',			'EventController@deleteEvent');


// SCHEDULE
Route::get('/schedule/',						'ScheduleController@showScheduleList');

Route::get('/schedule/id/{id}',					'ScheduleController@showSchedule');

Route::post('/schedule/id/{id}',				'ScheduleController@updateSchedule');


// TASK
Route::get('/task',								'ScheduleController@showTaskList');								

Route::get('/task/id/{id}',						'ScheduleController@showSchedule');

Route::post('/task/id/{id}',					'ScheduleController@updateSchedule');

Route::get('/task/id/{id}/edit',				'TaskController@showEditTask');

Route::post('/task/id/{id}/edit', 				array('as' => 'editTask',
													  'uses' => 'TaskController@editTask'));

Route::get('/task/id/{id}/delete',				'TaskController@deleteTask');


// AUTHENTIFICATION
Route::get('login',								'CalendarController@currentMonth');

Route::post('login', 							'LoginController@doLogin');

Route::get('logout', 							'LoginController@doLogout');

Route::post('logout', 							'LoginController@doLogout');


//CREATE
Route::get('/task/create',						'TaskController@showCreateTask');

Route::post('/task/create', 					array('as' => 'newTask',
													  'uses' => 'TaskController@createTask'));

Route::get('/calendar/create',					'EventController@showCreateEventToday');

Route::get('/calendar/create/{year}/{month}/{day}',	
												'EventController@showCreateEvent');

Route::get('/calendar/create/{year}/{month}/{day}/template/{id}', 
												array('as' => 'templateSelect',
												 	  'uses' => 'EventController@showCreateEventWithTemplate'));

Route::post('/calendar/create', 				array('as' => 'newClubEvent',
													  'uses' => 'EventController@createEvent'));

Route::get('/calendar/create/template/{id}', 	'EventController@showCreateEventTodayWithTemplateToday');


//STATISTICS
/* Route::get('/statistics',						'StatisticsController@showStatistics');

Route::post('/statistics', 						array('as' => 'statisticsChangeDate',
													  'uses' => 'StatisticsController@showStatistics'));
*/

// MANAGEMENT
Route::get('/management/jobtypes',				'ManagementController@showJobTypes');

Route::post('/management/jobtypes', 			array('as' => 'updateJobTypes',
													  'uses' => 'ManagementController@updateJobTypes'));

Route::get('/management/places',				'ManagementController@showPlaces');

Route::post('/management/places', 				array('as' => 'updatePlaces',
													  'uses' => 'ManagementController@updatePlaces'));

Route::get('/management/templates',				'ManagementController@showTemplates');

Route::post('/management/templates', 			array('as' => 'updateTemplates',
													  'uses' => 'ManagementController@updateTemplates'));


// TESTING AJAX

   Route::get('/ajax', 'CalendarController@showAjax');

   Route::post('/ajax/posted', 'CalendarController@posted');

