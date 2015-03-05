This is a **development version** - NOT FOR PRODUCTION USE.

## About
**Lara** ("**Ve**rwaltung **d**es **St**udentenclubs") is a planning tool that combines a calender with personnel scheduling, finances and internal statistics. It is developed for [bc-Studentenclub](http://www.bc-club.de) and intended to scale to all Ilmenauer Studentenclub e.V. sections. 
Lara started as a softwareproject at [TU Ilmenau](http://tu-ilmenau.de) in WS2014/2015.

**This is work in progress!**
Some features are missing. 
Some bugs are still alive.

Contributors welcome - contact [Maxim](https://github.com/4D44H) if you want to help.

## Components: 
- **Calender events** tied to **event schedules** (for personnel planning)
- Separated **internal tasks** (for schedules without corresponding events)
- **Personnel statistics** and **event statistics**
- **Finances** und **controlling**
- **Authentification**, separating public calender events+schedules from internal data

## Roadmap
- **Phase 1** (finished): softwareproject at TU Ilmenau, components *calender*, *schedules* and *authentification* via bc-Club LDAP-Server implemented.
- **Phase 2** (in progress): personal project, components *statistics*, *finances* and *controlling* are in development.

## Current project status
(as of 01.03.2015)

- Calender: **OK**
- Schedules:
  - Event schedules: **OK**
  - Task schedules: **OK**
- Personnel statistics: *in progress*
- Event statistics: tba
- Finances: tba
- Controlling: tba

For detailed list open requirements and change-requests see: [Issues](https://github.com/4D44H/lara-vedst/issues).

## Requirements
- MySQL Database
- Apache Server
- PHP 5.6
 - MCrypt PHP extension
 - JSON PHP extension
 - LDAP PHP extension
 - MySQL PHP extension
- Composer
- Laravel 4.2 
 
## Installation
tba

## Licence 
Code published under [GNU GPL v.3](https://github.com/4D44H/lara-vedst/blob/master/LICENSE).

Lara is based on: 
- [Laravel 4.2](http://laravel.com)
- [Bootstrap 3.3.1](http://getbootstrap.com) + [Bootswatch 3.3.1+2](http://bootswatch.com)
- [JQuery 2.1.1](http://jquery.com)
- [Font Awesome 4.2.0](http://fortawesome.github.io/Font-Awesome) 

(all licenced under [MIT Licence](http://opensource.org/licenses/mit-license.html)).
