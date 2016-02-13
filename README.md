## Club management software built on top of Laravel 5.2

## This is a development version
**Lara is a work in progress - and a beautiful mess sometimes.**
Some features are missing. 
Some bugs are still alive.
Use at your own risk.

## Current changes compared to the "master" branch
* ~~Switched from Laravel 5.0 to Laravel 5.1~~ *Changed to 5.2, see below.*
* Changed namespace from "App" to "Lara"
* Updated models, migrations and seeders to Laravel ~~5.1~~ 5.2 style
* Added relationships to all models, edited namespaces accordingly
* Created a RESTful Controller for every model, implementation following later
* Added some internal resources (master layout, logos, etc.)
* Updated Font Awesome to 4.5.0
* Wrote user-side AJAX requests-responses, database updates on the server-side following, as well as some validation and changes highlighting via CSS
* Wrote database updates for AJAX requests in ScheduleEntry changes
* Restructured ScheduleEntryById views, separated into multiple partials and removed table formatting -> divs instead
* Returned event creation and deletion functionality
* Event creation: "accept template" button gone, a click on a template name redirects to this template directly
* Added placeholders for internal events for external guest, opened week view for all
* Updated Laravel to 5.2 version. All systems nominal, keep flying!


## Contributing
For a detailed list of open requirements and change-requests visit [Issues](https://github.com/4D44H/lara-vedst/issues) and/or contact [Maxim](https://github.com/4D44H) if you want to help.
 

### License
Code published under [GNU GPL v.3](https://github.com/4D44H/lara-vedst/blob/master/LICENSE).

Lara VedSt is based on: 
- [Laravel 5.2](http://laravel.com)
- [Bootswatch 3.3.4+1](http://bootswatch.com)
- [JQuery 2.1.3](http://jquery.com)
- [Font Awesome 4.5.0](http://fortawesome.github.io/Font-Awesome) 
- [Isotope 2.2.0](http://isotope.metafizzy.co/)

(All licensed under [MIT License](http://opensource.org/licenses/MIT) or [GNU GPL v.3](http://opensource.org/licenses/GPL-3.0)).
