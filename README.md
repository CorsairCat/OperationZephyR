# OperationZephyR
+ an automatic generator of calendar
+ example is here: http://www.corsaircat.com/operationZ/index.php ;

## This should be include to make this project run
* check the generator of ical from below link
* https://github.com/markuspoerschke/iCal

## Installation Guide
* what you need: apache2 or nginx; php-fpm 7; php composer; a mysql/mariadb;
* STEP1: create a database called zephyr;
* STEP2: create 2 table in database zephyr:
* table1 : account
+ id int(11) NOT NULL PRIMARY KEY auto_increment
+ username varchar(100) NOT NULL
+ userinfo varchar(2000) NOT NULL
* table2 : course
+ id int(11) NOT NULL PRIMARY KEY auto_increment
+ courseid varchar(200) NOT NULL
+ coursename varchar(200) NOT NULL
+ coursetype varchar(200) NOT NULL
* STEP3: put the whole file into your nginx folder;
* STEP4: use the composer to install the ical moudle;
* STEP5: input the csv of course info to the stable && run the expendCsv.php through the browser && delete the expendCsv for safity;
* STEP6: enjoy it;
