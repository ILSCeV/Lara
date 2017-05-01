<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
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
Route::get('person/{query?}', 				'PersonController@index');
Route::get('club/{query?}', 				'ClubController@index');
Route::get('statistics/person/{query?}', 	'StatisticsController@shiftsByPerson');
Route::get('jobtypes/{query?}', 			'JobtypeController@find');

// additional route to store a SurveyAnswer
Route::post('survey/{survey}/storeAnswer', 'SurveyController@storeAnswer');

// Language
Route::get('lang/{lang}', 'LanguageController@switchLang')->name('lang.switch');

Route::get('lang', function() {
    return response()->json(['language' => Session::get('applocale')]);
});

// RESTful RESOURCES
Route::resource('jobtype', 	'JobtypeController');
Route::resource('entry', 	'ScheduleEntryController', 	['except' => ['index', 'create', 'store', 'edit', 'destroy']]);
Route::resource('schedule', 'ScheduleController', 		['except' => ['index', 'create', 'store', 'edit', 'destroy']]);
Route::resource('event', 	'ClubEventController', 		['except' => ['index']]);
Route::resource('person', 	'PersonController', 		['only'   => ['index']]);
Route::resource('club', 	'ClubController', 			['only'   => ['index']]);
Route::resource('survey',	'SurveyController',			['except' => ['index']]);
Route::resource('survey.answer', 'SurveyAnswerController', ['only' => ['show', 'store', 'update', 'destroy']]);


// STATISTICS
Route::get('/statistics/{year?}/{month?}',	'StatisticsController@showStatistics');


// JSON EXPORT - RETURNS EVENTS METADATA
Route::get('export/{clb_id}/{date_start}/{nr_of_events}', function($clb_id, $date_start, $nr_of_events) {

	$results = \Lara\ClubEvent::where('plc_id', '=', $clb_id)
							  ->where('evnt_is_private', '=', '0')
							  ->where('evnt_date_start', '>=', $date_start)
							  ->whereIn('evnt_type', [0,2,3])
							  ->orderBy('evnt_date_start')
							  ->orderBy('evnt_time_start')
							  ->take((0 <= $nr_of_events) && ($nr_of_events <= 20) ? $nr_of_events : 20)	// setting output size range to 0-20
							  ->get(['id', 'evnt_title', 'evnt_date_start', 'evnt_time_start']);

	return response()->json($results, 200, [], JSON_UNESCAPED_UNICODE);
});

// ICAL: CALENDAR FEED
Route::get('/ical/public/allevents', 							'IcalController@allPublicEvents' )->name("icalallevents");
Route::get('/ical/feed/{location}/{with_private_info?}', 		'IcalController@events');
Route::get('/ical/location/{location}/{with_private_info?}', 	'IcalController@events');
Route::get('/ical/events/user/{club_id}/{alarm?}', 				'IcalController@userScheduleWithAlarm');
Route::get('/ical/links', 										'IcalController@generateLinks');
Route::get('/ical/event/{evt_id}',								'IcalController@singleEvent');
Route::get('/ical/event/{id}/publish', 							'IcalController@togglePublishState')->name("togglePublishState");
