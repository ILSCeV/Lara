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
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Lara\Person::class, function (Faker\Generator $faker) {
    return [
        'prsn_name' => $faker->name(),
        'prsn_ldap_id' => $faker->numberBetween(4000, 8000),
        'prsn_status' => $faker->randomElement(['member', 'veteran', 'candidate']),
        'clb_id' => $faker->randomElement([2,3])
    ];
});


$factory->define(Lara\Survey::class, function (Faker\Generator $faker) {
    return [
        'creator_id' => factory(Lara\Person::class)->create()->id,
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
