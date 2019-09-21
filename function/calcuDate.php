<?php
    //统一使用 YYYY-MM-DD 格式 H:i:s

    function getCourseDateTime($week,$day,$time){
        $week = $week - 1;
        $hour = intval($time);
        $min = "00";
        if ($time > $hour){
            $min = "30";
        }
        if ($hour < 10){
            $hourString = " 0".$hour;
        }else{
            $hourString = " ".$hour;
        }
        include_once "weekRangeConfig.php"; //引入日期操作变量
        $actuallTime = "2019-09-16".$hourString.":".$min.":00";
        $thisCourseTime = strtotime("$actuallTime +$week week $day day");
        return date('Y-m-d H:i:s', $thisCourseTime);
    }
?>