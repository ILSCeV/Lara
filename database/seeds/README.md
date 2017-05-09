## laravel seeder based on iSeed

A seeder fills the database with example entries for development environemts

https://laravel.com/docs/master/seeding

**Do not use on production installations! These commands will wipe your database!**

#### Usage

There are two main files used for seeding:
- `database/seeds/DatabseSeeder`: This file specifies which seeds to use. Order is important here, as some seeds depend on other values being existant (e.g. you can't have an event without a place or creator).
- `database/factories/ModelFactory`: Here you can describe the shape of the data that will be inserted into the database. This uses the faker package provided by laravel (for detailed use see [Faker](https://github.com/fzaninotto/Faker)).

Seeding the databse will create places, persons and events according to the templates defined in the `ModelFactory`. This allows use to use fake data in developement without always having to dump the exisiting production database.

To seed the database, use 
``` 
php artisan db:seed
```
Again ** This command will delete all your databse entries! ** 
