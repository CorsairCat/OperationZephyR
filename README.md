# OperationZephyR
+ an automatic generator of calendar
+ example is here: http://www.corsaircat.com/operationZ/ ;

## This should be include to make this project run
* check the generator of ical from below link
* https://github.com/markuspoerschke/iCal

## 2019-11-25 UPDATE
+ adding support for wechat automatical reply

## 2019-09-26 UPDATE
+ from the latest user record, add more detect to prevent user's wrong usage, such as use the y234system to search y1 data

## 2019-09-25 UPDATE
+ now it support the y1 timetable generation

## 2019-09-24 UPDATE
+ we improved the function for some special condition;
+ one more step is needed for generate location for some courses;
+ add the function for the timetable changed situation; 

## Installation Guide
* what you need: apache2 or nginx; php-fpm 7; php composer; a mysql/mariadb;
* STEP1: create a database called zephyr;
* STEP2: create 3 table in database zephyr:
* table1 : account
+ id int(11) NOT NULL PRIMARY KEY auto_increment
+ username varchar(100) NOT NULL
+ userinfo varchar(2000) NOT NULL
* table2 : course
+ id int(11) NOT NULL PRIMARY KEY auto_increment
+ courseid varchar(200) NOT NULL
+ coursename varchar(200) NOT NULL
+ coursetype varchar(200) NOT NULL
* table3 : speciallocation
+ id int(11) NOT NULL PRIMARY KEY auto_increment
+ courseName varchar(200) NOT NULL
+ courseLocation varchar(200) NOT NULL
* STEP3: some thing you need to change before upload: 
* |- "function/calcuDate.php" (the start date (Monday) inside it: in the download file it would be 2019-09-16)
* |- "config/dbconfig/maindb.php" (change the variables of user, pass and host to access the database on your own server)
* STEP4: put the whole file into your nginx folder;
* STEP5: use the composer to install the ical moudle;
* STEP6: input the csv of course info to the stable && run the expendCsv.php and expendCsv2.php through the browser && delete the expendCsv and expendCsv2 for safity;
* STEP7: enjoy it;
