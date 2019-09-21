<?php
    include_once "calcuDate.php";

    function geneCal($array){
        // use composer autoloader
        require_once __DIR__ . '/../vendor/autoload.php';
        // set default timezone (PHP 5.4)
        date_default_timezone_set('Asia/Shanghai');
        // 1. Create new calendar
        $vCalendar = new \Eluceo\iCal\Component\Calendar('www.corsaircat.com');
        // 2. Create events
        for ($d=0;$d<5;$d++){
            $dayArray = $array[$d];
            for ($i=0;$i<count($array[$d]);$i++){
                $eventNum = count($dayArray[$i][4]);
                for ($j=0;$j<$eventNum;$j++){
                    $timeStart = getCourseDateTime($dayArray[$i][4][$j],$d,$dayArray[$i][5]);
                    $timeEnd = getCourseDateTime($dayArray[$i][4][$j],$d,$dayArray[$i][6]);
                    $vEvent = new \Eluceo\iCal\Component\Event();
                    $vEvent->setDtStart(new \DateTime($timeStart));
                    $vEvent->setDtEnd(new \DateTime($timeEnd));
                    $vEvent->setSummary($dayArray[$i][0]);
                    $vEvent->setDescription("teacher: ".$dayArray[$i][3]);
                    $vEvent->setDescriptionHTML('<b> teacher'.$dayArray[$i][3].'</b>');
                    // add some location information
                    $vEvent->setLocation($dayArray[$i][2]);
                    $vCalendar->addComponent($vEvent);
                }
            }
        }
        return $vCalendar;
    }
?>