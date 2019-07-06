<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Carbon\Carbon;
use Lara\Status;

$factory->define(Lara\User::class, function (Faker\Generator $faker) {
    static $password;
    $onLeave = $faker->boolean(20) ? $faker->dateTimeBetween('-5 months', '+6 months'):null;
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'on_leave' => $onLeave,
    ];
});

$factory->define(Lara\Person::class, function (Faker\Generator $faker) {
    $isUser = $faker->boolean(60);
    $clubQuery = \Lara\Club::query()->inRandomOrder();
    if($isUser) {
        $clubQuery->where('clb_title', '=', \Lara\Section::query()->inRandomOrder()->first()->title);
    }
    return [
        'prsn_name' => $faker->name(),
        'prsn_ldap_id' => $isUser ? $faker->numberBetween(2000, 9999):null,
        'prsn_status' => $faker->randomElement(Status::ACTIVE),
        'prsn_uid' => hash("sha512", uniqid()),
        'clb_id' => $clubQuery->first()->id
    ];
});


$factory->define(Lara\Survey::class, function (Faker\Generator $faker) {
    return [
        'creator_id' => Lara\Person::query()->whereNotNull('prsn_ldap_id')->inRandomOrder()->first()->id,
        'title' => $faker->sentence(2),
        'description' => $faker->paragraphs(4, true),
        'deadline' => $faker->dateTimeBetween('now', '+60 days')->format('Y-m-d H:i:s'),
        'password' => '',
        'is_private' => false,
        'is_anonymous' => false,
        'show_results_after_voting' => false
    ];
});

$factory->define(Lara\SurveyAnswer::class, function(Faker\Generator $faker){
    return [
        'creator_id' => Lara\Person::query()->whereNotNull('prsn_ldap_id')->inRandomOrder()->first()->id,
        'survey_id' => Lara\Survey::inRandomOrder()->first()->id,
        'name' => $faker->firstName,
        'club' => $faker->word
    ];
});

$factory->define(Lara\SurveyAnswerOption::class, function(Faker\Generator $faker){
    return [
        'survey_question_id' => Lara\SurveyQuestion::inRandomOrder()->first()->id,
        'answer_option' => $faker->sentence(),
    ];
});

$factory->define(Lara\SurveyAnswerCell::class, function(Faker\Generator $faker){
    return [
        'survey_answer_id' => Lara\SurveyAnswer::inRandomOrder()->first()->id,
        'survey_question_id' => Lara\SurveyQuestion::inRandomOrder()->first()->id,
        'answer' => $faker->sentence,
    ];
});

$factory->define(Lara\SurveyQuestion::class, function(Faker\Generator $faker){
    return [
        'survey_id' => Lara\Survey::inRandomOrder()->first()->id,
        'order' => $faker->numberBetween(0, 5),
        'field_type' => 1,
        'question' => $faker->sentence,
        'is_required' => 0
    ];
});

$factory->define(Lara\ClubEvent::class, function(Faker\Generator $faker) {
    $start = $faker->dateTimeBetween('-5 years', '+1 years');
    $end = $faker->dateTimeBetween($start, date("Y-m-d H:i:s", strtotime('+1 day', $start->getTimestamp())));
    $eventName = $faker->randomKey(EventNameDictionary::EVENT_NAMES);
    $eventType = EventNameDictionary::EVENT_NAMES[$eventName];
    return [
        'evnt_type' => $eventType,
        'evnt_title' => $eventName,
        'evnt_subtitle' => $faker->word(),
        'plc_id' => Lara\Section::inRandomOrder()->first()->id,
        'evnt_date_start' => $start->format('Y-m-d'),
        'evnt_date_end' => $end->format('Y-m-d'),
        'evnt_time_start' => $start->format('H:i'),
        'evnt_time_end' => $end->format('H:i'),
        'evnt_public_info' => $faker->sentence(),
        'evnt_private_details' => $faker->sentence(),
        'evnt_is_private' => $faker->boolean(10),
        'evnt_is_published' => 0,
        'price_tickets_normal'=> $faker->numberBetween(0,5),
        'price_tickets_external'=>$faker->numberBetween(0,10),
        'price_normal' => $faker->randomFloat(2,0,1),
        'price_external' => $faker->randomFloat(2,1,2),
        'facebook_done' => $faker->boolean(50),
        'event_url' => $faker->url(),
        'external_id'=> $faker->word()
    ];
});

$factory->define(Lara\ShiftType::class, function(Faker\Generator $faker) {
    $types = ['Einlass', 'Bar', 'Tresen', 'AV', 'Disko', 'Licht'];
    $end = $faker->time('H:i');
    $start = $faker->time('H:i', $end);
    return [
        'title' => $faker->randomElement($types),
        'start' => $start,
        'end' => $end,
        'needs_preparation' => $faker->boolean(),
        'statistical_weight' => $faker->numberBetween(0, 4) * 0.5,
        'is_archived' => 0
    ];
});

$factory->define(Lara\Schedule::class, function(Faker\Generator $faker) {
    return [
        'schdl_title' => $faker->word(),
        'schdl_time_preparation_start' => $faker->time('H:i'),
        'schdl_password' => '',
        'entry_revisions' => ''
    ];
});

$factory->define(Lara\Shift::class, function(Faker\Generator $faker) {
    $end = $faker->time('H:i');
    $start = $faker->time('H:i', $end);
    return [
        'start' => $start,
        'end' => $end,
        'statistical_weight' => 1,
        'position' => 0
    ];
});

$factory->define(Lara\Club::class, function(Faker\Generator $faker) {
    return [
        'clb_title' => $faker->randomElement(["bi-Club","bc-Club","FEM","iStuff","bh-Club","bc-Cafe","bd-Club","MFK","Band"]),
    ];
});

$factory->define(Lara\Template::class, function(Faker\Generator $faker) {
    $start = $faker->dateTimeBetween('-30 days', '+60 days');
    $end = $faker->dateTimeBetween($start, date("Y-m-d H:i:s", strtotime('+1 day', $start->getTimestamp())));
    $eventName = $faker->randomKey(EventNameDictionary::EVENT_NAMES);
    $eventType = EventNameDictionary::EVENT_NAMES[$eventName];
    return [
        'type' => $eventType,
        'title' => $eventName,
        'subtitle' => $faker->word(),
        'section_id' => Lara\Section::inRandomOrder()->first()->id,
        'time_start' => $start->format('H:i'),
        'time_end' => $end->format('H:i'),
        'public_info' => $faker->sentence(),
        'private_details' => $faker->sentence(),
        'is_private' => $faker->boolean(10),
        'price_tickets_normal'=> $faker->numberBetween(0,5),
        'price_tickets_external'=>$faker->numberBetween(0,10),
        'price_normal' => $faker->randomFloat(2,0,1),
        'price_external' => $faker->randomFloat(2,1,2),
        'facebook_needed' => $faker->boolean(40),
    ];
});

$factory->define(Lara\Role::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->title(),
        'section_id'=> (new Lara\Section)->inRandomOrder()->first()->id
    ];
});
