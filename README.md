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
 

## Installation on XAMPP on Windows##

```"$>" stands for console commands (Start --> run --> cmd)```

1. Install XAMPP - https://www.apachefriends.org/index.html

2. Install Composer - https://getcomposer.org/Composer-Setup.exe

3. Install git - https://windows.github.com/

4. Copy all files from 4D44H/lara-vedst to *C:/xampp/htdocs/lara-vedst*, e.g. using *GitHub For Windows* - clone *4D44H/lara-vedst*

5. Open project directory in the console - ```$> cd c:/xampp/htdocs/lara-vedst``` and update dependencies - ```$> composer update``` 
*(this one can take some time - don't worry, make some tea)*

6. In the Apache Server config (httpd.conf) change ```DocumentRoot "C:/xampp/htdocs``` to ```DocumentRoot "C:/xampp/htdocs/lara-vedst/public"``` to hide "/lara-vedst/public" path.

7. Start both the server and the database, e.g. from XAMPP Control Panel

8. Create a new MySQL database named "lara-vedst" in XAMPP (e.g. by using http://127.0.0.1/phpmyadmin ) and update database schema - ```$> php artisan migrate```

*At this point you should be able to run Lara VedSt without error here: http://127.0.0.1/ or http://localhost*
*For development purposes a workaround is built into the LoginController which assigns a random dummy-user at each login event, ignoring login/password input from user.*


## Installation on a CentOS production server##
```"$>" stands for console commands```

1. Install the server, e.g.: ```$> yum install httpd mariadb-server php php-ldap php-mysql```

2. Install Composer: 
  - open directory: ```$> cd /usr/local/bin/ ```
  - download composer: ```$> php -r "readfile('https://getcomposer.org/installer');" | php``` 
  - add a symbolic link: ```$> ln –s /usr/local/bin/composer.phar /usr/local/bin/composer```.

3. Install git: ```$> yum install git```

4. Copy lara-vedst files with git:
  - open directory: ```$> cd /var/www/```
  - clone repository with git: ```$> git clone https://github.com/4D44H/lara-vedst.git ```

5. Update dependencies: ```$> composer update``` 
*(this one can take some time - don't worry, make some tea)*

6. Change permissions: 
  - ```$> cd /var/www```
  - ```$> chown -R apache:apache lara-vedst/```
  - ```$> chmod -R 755 lara-vedst/```
  - ```$> chmod -R 777 lara-vedst/storage```

7. To hide "/lara-vedst/public" path:
  - open the Apache Server config (*actual filename may vary*): ```$> vi /etc/httpd/conf.d/laravel.conf```
  - type ```i``` to enter editing mode
  - change ```DocumentRoot``` to ```DocumentRoot "var/www/lara-vedst/public"```
  - hit ```<ESC>``` to exit editing mode
  - type ```:x``` and hit ```<Return>``` to exit *vi*

7. Restart the server: ```$> /etc/init.d/httpd restart```

8. Create a MySQL database named "lara-vedst":
  - switch to MySQL: ```$> mysql –u root –p```
  - create new database named "lara-vedst": ```$> create database lara-vedst```
  - update database schema from /lara-vedst folder: ```$> php artisan migrate```

9. Edit environment variables in the file ```.env``` located in ```/var/www/lara-vedst/```:
  - set "APP_ENV=production"
  - set "APP_DEBUG=false" 
  - enter new random key to "APP_KEY=" (32 signs)
  - set "DB_HOST", "DB_DATABASE", "DB_USERNAME" and "DB_PASSWORD" to your database credentials

10. For development purposes a workaround is built into the LoginController which assigns a random dummy-user at each login event, ignoring login/password input from user. Change it to the your actual authentification routine.


## Contributing

Visit [Issues](https://github.com/4D44H/lara-vedst/issues) and/or contact [Maxim](https://github.com/4D44H) if you want to help.


### License
Code published under [GNU GPL v.3](https://github.com/4D44H/lara-vedst/blob/master/LICENSE).

Lara is based on: 
- [Laravel 5.0](http://laravel.com)
- [Bootstrap 3.3.1](http://getbootstrap.com) + [Bootswatch 3.3.1+2](http://bootswatch.com)
- [JQuery 2.1.1](http://jquery.com)
- [Font Awesome 4.3.0](http://fortawesome.github.io/Font-Awesome) 

(All licensed under [MIT License](http://opensource.org/licenses/MIT)).
