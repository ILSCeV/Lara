<?php

/*
|--------------------------------------------------------------------------
| Global Patterns
|--------------------------------------------------------------------------
| 
|  Route::pattern('id', '[0-9]+'); // only once
| 
|  Route::get('/aaa/bbb/{id}', '...');
|  Route::get('/ccc/ddd/{id}', '...');
| 
|  instead of
| 
|  Route::get('/aaa/bbb/{id}', '...')->where('id', '[0-9]+');"
|  Route::get('/ccc/ddd/{id}', '...')->where('id', '[0-9]+');" 
| 
*/ 

// GLOBAL PATTERNS
Route::pattern('id', 	'[0-9]+');
Route::pattern('year', 	'[2][0][0-9][0-9]');
Route::pattern('month',	'[0][1-9]|[1][0-2]');
Route::pattern('week', 	'[0-5][0-9]');
Route::pattern('day', 	'[0-3][0-9]');


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


// DEFAULT
Route::get('/', 								'CalendarController@currentMonth');
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

Route::post('/calendar/{year}/KW{week}',		['as'   => 'bulkUpdate', 
												 'uses' => 'ScheduleController@bulkUpdateSchedule']);


// DAY
Route::get('/calendar/today',					'CalendarController@currentDay');
Route::get('/calendar/{year}/{month}/{day}',	'CalendarController@showDate');


// EVENT ID
Route::get('/calendar/id/{id}', 				'CalendarController@showId');
Route::get('/calendar/id/{id}/edit',			'EventController@showEditEvent');
Route::get('/calendar/id/{id}/delete',			'EventController@deleteEvent');

Route::post('/calendar/id/{id}/edit', 			['as'   => 'editClubEvent', 
												 'uses' => 'EventController@editEvent']);


// SCHEDULE
Route::get('/schedule/',						'ScheduleController@showScheduleList');
Route::get('/schedule/id/{id}',					'ScheduleController@showSchedule');

Route::post('/schedule/id/{id}',				'ScheduleController@updateSchedule');


// TASK
Route::get('/task',								'ScheduleController@showTaskList');								
Route::get('/task/id/{id}',						'ScheduleController@showSchedule');
Route::get('/task/id/{id}/edit',				'TaskController@showEditTask');
Route::get('/task/id/{id}/delete',				'TaskController@deleteTask');

Route::post('/task/id/{id}',					'ScheduleController@updateSchedule');
Route::post('/task/id/{id}/edit', 				['as'   => 'editTask',
												 'uses' => 'TaskController@editTask']);


// AUTHENTIFICATION
Route::get('login',								'CalendarController@currentMonth');
Route::get('logout', 							'LoginController@doLogout');

Route::post('login', 							'LoginController@doLogin');
Route::post('logout', 							'LoginController@doLogout');


// CREATE
Route::get('/task/create',						'TaskController@showCreateTask');
Route::get('/calendar/create',					'EventController@showCreateEventToday');
Route::get('/calendar/create/{year}/{month}/{day}',	
												'EventController@showCreateEvent');
Route::get('/calendar/create/{year}/{month}/{day}/template/{id}', 
												['as'   => 'templateSelect',
												 'uses' => 'EventController@showCreateEventWithTemplate']);
Route::get('/calendar/create/template/{id}', 	'EventController@showCreateEventTodayWithTemplateToday');

Route::post('/task/create', 					['as'   => 'newTask',
												 'uses' => 'TaskController@createTask']);
Route::post('/calendar/create', 				['as'   => 'newClubEvent',
												 'uses' => 'EventController@createEvent']);


// STATISTICS
/* 
Route::get('/statistics',						'StatisticsController@showStatistics');

Route::post('/statistics', 						['as'   => 'statisticsChangeDate',
											     'uses' => 'StatisticsController@showStatistics']);
*/


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




////////////////////////////////////////////////////////////////
// BETA VERSION - TESTING BELOW THIS LINE, WORKING CODE ABOVE //
////////////////////////////////////////////////////////////////




Route::get('/beta', 							function() { return View::make('betatest-index'); });


