<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
|--------------------------------------------------------------------------
| Default Laravel routes for reference
|--------------------------------------------------------------------------
| 
| Route::get('/', function () {
|     return view('welcome');
|  });
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
Route::get('logs', 								'LogViewerController@index');


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


// AJAX calls
Route::get('person/{query?}', 'PersonController@index');
Route::get('club/{query?}', 'ClubController@index');

// additional route to store a SurveyAnswer
Route::post('survey/{survey}/storeAnswer', 'SurveyController@storeAnswer');

// Language
Route::get('lang/{lang}', ['as'=>'lang.switch', function($lang){
	if (array_key_exists($lang, Config::get('languages'))) {
            Session::set('applocale', $lang);
        }
    return Redirect::back();
}]);

// RESTful RESOURCES
Route::resource('entry', 	'ScheduleEntryController', 	['except' => ['index', 'create', 'store', 'edit', 'destroy']]);
Route::resource('schedule', 'ScheduleController', 		['except' => ['index', 'create', 'store', 'edit', 'destroy']]);
Route::resource('event', 	'ClubEventController', 		['except' => ['index']]);
Route::resource('person', 	'PersonController', 		['only'   => ['index']]);
Route::resource('club', 	'ClubController', 			['only'   => ['index']]);
Route::resource('survey',	'SurveyController',			['except' => ['index']]);
Route::resource('survey.answer', 'SurveyAnswerController', ['only' => ['show', 'store', 'update', 'destroy']]); //show only for testing purposes


/*
// STATISTICS
Route::get('/statistics',						'StatisticsController@showStatistics');

Route::post('/statistics', 						['as'   => 'statisticsChangeDate',
											     'uses' => 'StatisticsController@showStatistics']);
*/
/*
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
*/