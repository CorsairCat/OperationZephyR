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
                    $timeStart = getCourseDateTime($dayArray[$i][4][$j],$d,$dayArray[$i][6]);
                    $timeEnd = getCourseDateTime($dayArray[$i][4][$j],$d,$dayArray[$i][7]);
                    $vEvent = new \Eluceo\iCal\Component\Event();
                    $vEvent->setDtStart(new \DateTime($timeStart));
                    $vEvent->setDtEnd(new \DateTime($timeEnd));
                    $vEvent->setSummary($dayArray[$i][0]);
                    $vEvent->setDescription("courseType: ".$dayArray[$i][5]."; teacher: ".$dayArray[$i][3]);
                    $vEvent->setDescriptionHTML('<b>'."courseType: ".$dayArray[$i][5]."; teacher".$dayArray[$i][3].'</b>');
                    // add some location information
                    $vEvent->setLocation($dayArray[$i][2]);
                    $vCalendar->addComponent($vEvent);
                }
            }
        }
        return $vCalendar;
    }

    function geneCalWithAlarm($array,$alarmTime){
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
                    $timeStart = getCourseDateTime($dayArray[$i][4][$j],$d,$dayArray[$i][6]);
                    $timeEnd = getCourseDateTime($dayArray[$i][4][$j],$d,$dayArray[$i][7]);
                    $vEvent = new \Eluceo\iCal\Component\Event();
                    $vEvent->setDtStart(new \DateTime($timeStart));
                    $vEvent->setDtEnd(new \DateTime($timeEnd));
                    $vEvent->setSummary($dayArray[$i][0]);
                    $vEvent->setDescription("courseType: ".$dayArray[$i][5]."; teacher: ".$dayArray[$i][3]);
                    $vEvent->setDescriptionHTML('<b>'."courseType: ".$dayArray[$i][5]."; teacher".$dayArray[$i][3].'</b>');
                    // add some location information
                    $vEvent->setLocation($dayArray[$i][2]);

                    $vAlarm = new \Eluceo\iCal\Component\Alarm;
                    $vAlarm->setAction(\Eluceo\iCal\Component\Alarm::ACTION_DISPLAY);
                    $vAlarm->setDescription("courseType: ".$dayArray[$i][5]."; teacher: ".$dayArray[$i][3]);
                    $triggerTime = "-PT".$alarmTime."M";
                    $vAlarm->setTrigger($triggerTime, true);
                    $vEvent->addComponent($vAlarm);

                    $vCalendar->addComponent($vEvent);
                }
            }
        }
        return $vCalendar;
    }
?>