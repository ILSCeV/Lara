<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/* Route::get('/', function()
	{
		return View::make('index');
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

Route::get('/calendar/{year}/month/{month}',	'MonthController@showMonth');


// WEEK
Route::get('/calendar/week/{id}', 				'WeekController@showId');

Route::get('/calendar/week', 					'WeekController@currentWeek');

Route::get('/calendar/{year}/KW{week}', 		'WeekController@showWeek');

Route::get('/calendar/{year}/week/{week}', 		'WeekController@showWeek');

Route::post('/calendar/{year}/KW{week}',		array('as' => 'bulkUpdate', 'before' => 'csrf', 'uses' => 'ScheduleController@bulkUpdateSchedule'));


// DAY
Route::get('/calendar/today',					'CalendarController@currentDay');

Route::get('/calendar/{year}/{month}/{day}',	'CalendarController@showDate');


// EVENT ID
Route::get('/calendar/id/{id}', 				'CalendarController@showId');

Route::get('/calendar/id/{id}/edit',			'EventController@showEditEvent');

Route::post('/calendar/id/{id}/edit', 			array('as' => 'editClubEvent', 'before' => 'csrf', 'uses' => 'EventController@editEvent'));

Route::get('/calendar/id/{id}/delete',			'EventController@deleteEvent');


// SCHEDULE
Route::get('/schedule/',						'ScheduleController@showScheduleList');

Route::get('/schedule/id/{id}',					'ScheduleController@showSchedule');

Route::post('/schedule/id/{id}',				array('before' => 'csrf', 'ScheduleController@updateSchedule'));


// TASK
Route::get('/task',								'ScheduleController@showTaskList');								

Route::get('/task/id/{id}',						'ScheduleController@showSchedule');

Route::post('/task/id/{id}',					array('before' => 'csrf', 'uses' => 'ScheduleController@updateSchedule'));

Route::get('/task/id/{id}/edit',				'TaskController@showEditTask');

Route::post('/task/id/{id}/edit', 				array('as' => 'editTask', 'before' => 'csrf', 'uses' => 'TaskController@editTask'));

Route::get('/task/id/{id}/delete',				'TaskController@deleteTask');


// AUTHENTIFICATION
Route::get('login',								'CalendarController@currentMonth');

Route::post('login', 							array('before' => 'csrf', 'uses' => 'LoginController@doLogin'));

Route::get('logout', 							array('uses' => 'LoginController@doLogout'));

Route::post('logout', 							array('before' => 'csrf', 'uses' => 'LoginController@doLogout'));


//CREATE
Route::get('/task/create',						'TaskController@showCreateTask');

Route::post('/task/create', 					array('as' => 'newTask', 'before' => 'csrf', 'uses' => 'TaskController@createTask'));

Route::get('/calendar/create',					'EventController@showCreateEventToday');

Route::get('/calendar/create/{year}/{month}/{day}',	'EventController@showCreateEvent');

Route::get('/calendar/create/{year}/{month}/{day}/template/{id}', 	array('uses' => 'EventController@showCreateEventWithTemplate'));

Route::post('/calendar/create', 				array('as' => 'newClubEvent', 'before' => 'csrf', 'uses' => 'EventController@createEvent'));

Route::get('/calendar/create/template/{id}', 	array('uses' => 'EventController@showCreateEventTodayWithTemplateToday'));


//STATISTICS
Route::get('/statistics',						'StatisticsController@showStatistics');

Route::post('/statistics', 						array('as' => 'statisticsChangeDate', 'before' => 'csrf', 
													  'uses' => 'StatisticsController@showStatistics'));

// MANAGEMENT
Route::get('/management/jobtypes',				'ManagementController@showJobTypes');

Route::post('/management/jobtypes', 			array('as' => 'updateJobTypes', 'before' => 'csrf', 
													  'uses' => 'ManagementController@updateJobTypes'));
