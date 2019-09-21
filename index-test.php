<?php
?>


<?php 

    //include 'html/index.html';
    include 'function/getUserCalendar.php';

    echo "<br>";
    $userId = "20124992";
    $str = getOriginData($userId);
    echo "<br>";
    echo analyseHeader($str);
    echo "<br>";
    echo "<br><br>";
    $dayStr = getEveryDay($str);
    $days = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
    $infoList = array("courseName1","courseName2","Room","Teacher","Week","Start","End");
    for ($k=0;$k<7;$k++){
        echo "<h1>DAY:  ";
        echo $days[$k]."</h1><br>";
        $dayInfo = analyseDayCourse($dayStr[$k]);
        for ($i=0;$i<count($dayInfo);$i++){
            echo "course : ".($i+1)."<br>";
            for ($j=0;$j<7;$j++){
                echo $infoList[$j]." : ".$dayInfo[$i][$j];
                echo "<br>";
            }
            echo "<br><br>";
        }
        echo "<br>_________________________________________<br>";
    }
?>