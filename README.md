## Club management software built on top of Laravel 5.0

This is a **development version** - NOT FOR PRODUCTION USE.

## About
**Lara** ("**Ve**rwaltung **d**es **St**udentenclubs") is a planning tool that combines a calender with personnel scheduling, finances and internal statistics. It is developed for [bc-Studentenclub](http://www.bc-club.de) and intended to scale to all Ilmenauer Studentenclub e.V. sections. 
Lara started as a softwareproject at [TU Ilmenau](http://tu-ilmenau.de) in WS2014/2015.

**This is work in progress!**
Some features are missing. 
Some bugs are still alive.

## Current project status
(as of 01.04.2015)

- Calender: **OK**
- Schedules:
  - Event schedules: **OK**
  - Task schedules: **OK**
- Personnel statistics: *in progress*
- Event statistics: tba
- Finances: tba
- Controlling: tba

For detailed list open requirements and change-requests see: [Issues](https://github.com/4D44H/lara-vedst/issues).

## Components: 
- **Calender events** tied to **event schedules** (for personnel planning)
- Separated **internal tasks** (for schedules without corresponding events)
- **Personnel statistics** and **event statistics**
- **Finances** und **controlling**
- **Authentification**, separating public calender events+schedules from internal data

## Roadmap
- **Phase 1** (finished): softwareproject at TU Ilmenau, components *calender*, *schedules* and *authentification* via bc-Club LDAP-Server implemented.
- **Phase 2** (in progress): personal project, bugfixes and components *statistics*, *finances* and *controlling* are in development.

## Requirements
- MySQL Database
- Apache Server
- PHP >= 5.4
 - Mcrypt PHP Extension
 - OpenSSL PHP Extension
 - Mbstring PHP Extension
 - Tokenizer PHP Extension
- Composer

- Laravel 5.0 is already included in this repository.
 
## Installation

tba

## Contributing

Visit [Issues](https://github.com/4D44H/lara-vedst/issues) and/or contact [Maxim](https://github.com/4D44H) if you want to help.

### License
Code published under [GNU GPL v.3](https://github.com/4D44H/lara-vedst/blob/master/LICENSE).

Lara is based on: 
- [Laravel 5.0](http://laravel.com)
- [Bootstrap 3.3.1](http://getbootstrap.com) + [Bootswatch 3.3.1+2](http://bootswatch.com)
- [JQuery 2.1.1](http://jquery.com)
- [Font Awesome 4.3.0](http://fortawesome.github.io/Font-Awesome) 

(All licensed under [MIT License](http://opensource.org/licenses/MIT).
