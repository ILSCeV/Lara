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

$factory->define(Lara\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Lara\Person::class, function (Faker\Generator $faker) {
    return [
        'prsn_name' => $faker->name(),
        'prsn_ldap_id' => $faker->numberBetween(4000, 8000),
        'prsn_status' => $faker->randomElement(['member', 'veteran', 'candidate']),
        'prsn_uid' => hash("sha512", uniqid()),
        'clb_id' => $faker->randomElement([2,3])
    ];
});


$factory->define(Lara\Survey::class, function (Faker\Generator $faker) {
    return [
        'creator_id' => factory(Lara\Person::class)->create()->prsn_ldap_id,
        'title' => $faker->sentence(2),
        'description' => $faker->paragraphs(4, true),
        'description' => $faker->paragraphs(4, true),
        'deadline' => $faker->dateTimeBetween('now', '+10 days'),
        'password' => '',
        'is_private' => false,
        'is_anonymous' => false,
        'show_results_after_voting' => false
    ];
});

$factory->define(Lara\SurveyAnswer::class, function(Faker\Generator $faker){
    return [
        'creator_id' => factory(Lara\Person::class)->create()->prsn_ldap_id,
        'survey_id' => factory(Lara\Survey::class)->create()->id,
        'name' => $faker->firstName,
        'club' => $faker->word
    ];
});

$factory->define(Lara\SurveyAnswerOption::class, function(Faker\Generator $faker){
    return [
        'survey_question_id' => factory(Lara\SurveyQuestion::class)->create()->id,
        'answer_option' => $faker->sentence(),
    ];
});

$factory->define(Lara\SurveyAnswerCell::class, function(Faker\Generator $faker){
    return [
        'survey_answer_id' => factory(Lara\SurveyAnswer::class)->create()->id,
        'survey_question_id' => factory(Lara\SurveyQuestion::class)->create()->id,
        'answer' => $faker->sentence,
    ];
});

$factory->define(Lara\SurveyQuestion::class, function(Faker\Generator $faker){
    return [
        'survey_id' => factory(Lara\Survey::class)->create()->id,
        'order' => $faker->numberBetween(0, 5),
        'field_type' => $faker->numberBetween(1,3),
        'question' => $faker->sentence,
        'is_required' => 0
    ];
});
