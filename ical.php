<?php
// use composer autoloader
require_once __DIR__ . '/vendor/autoload.php';
// set default timezone (PHP 5.4)
date_default_timezone_set('Asia/Shanghai');
// 1. Create new calendar
$vCalendar = new \Eluceo\iCal\Component\Calendar('www.corsaircat.com');
// 2. Create an event

$vEvent = new \Eluceo\iCal\Component\Event();
$vEvent->setDtStart(new \DateTime('2019-09-22 05:00:00'));
$vEvent->setDtEnd(new \DateTime('2019-09-22 06:00:00'));
$vEvent->setSummary('LoLoLo');
$vEvent->setDescription('Happy TEST!');
$vEvent->setDescriptionHTML('<b>Happy TEST!</b>');
// add some location information for apple device
$vEvent->setLocation("PB001");

$cEvent = new \Eluceo\iCal\Component\Event();
$cEvent->setDtStart(new \DateTime('2019-09-22 07:00:00'));
$cEvent->setDtEnd(new \DateTime('2019-09-22 08:00:00'));
$cEvent->setSummary('LoLoLoXX');
$cEvent->setDescription('Happy TEST!?');
$cEvent->setDescriptionHTML('<b>Happy TEST!?</b>');
// add some location information for apple device
$cEvent->setLocation("PB002");


// 3. Add event to calendar
$vCalendar->addComponent($vEvent);
$vCalendar->addComponent($cEvent);
// 4. Set headers
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="cal.ics"');
// 5. Output
echo $vCalendar->render();
?>