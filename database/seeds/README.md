## laravel seeder based on iSeed

A seeder fills the database with example entries for development environemts

https://laravel.com/docs/master/seeding

**Do not use on production installations!**

#### Usage

* At installation, tables will be cleared and filled with example entries from files in this folder
* Entries generated from inside the Laravel app during runtime can be transfered to these files by iSeed: https://github.com/orangehill/iseed

Generation: `php artisan iseed club_events,schedules,persons,jobtypes,places,clubs,schedule_entries,survey,survey_question,survey_answer`

Seeding: `php artisan migrate:refresh && composer dumpautoload && php artisan db:seed`
