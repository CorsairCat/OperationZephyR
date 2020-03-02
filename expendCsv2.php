<?php
    include 'function/dbfunction.php';

    $dir="./stable/";
    $tmpArray = scandir($dir);
    $fileArray = array();
    for ($j=0;$j<count($tmpArray);$j++){
        if (strpos($tmpArray[$j],".csv") != FALSE){
            array_push($fileArray,$tmpArray[$j]);
        }
    }
    echo print_r($fileArray);
    $couserArray = array();
    $dataBase = "zephyr2020spring";
    $tableName = "speciallocation";
    for ($i=0;$i<count($fileArray);$i++){
        $file = fopen("stable/".$fileArray[$i],"r");
        while(! feof($file)){
            $tmp = fgetcsv($file);
            if ($tmp != NULL){
                if ($tmp[2] != "" AND $tmp[2] != "Name of Type"){
                    array_push($couserArray,$tmp);
                    $signal = stripos($tmp[0],"refer");
                    if ($signal == FALSE){
                        addData($dataBase,$tableName,2,array("courseName","courseLocation"),array($tmp[0],$tmp[8]));
                        echo "<br>$tmp[0]: $tmp[8]";
                    }else{
                        $signal2 = strripos($tmp[0],")");
                        $signal3 = stripos($tmp[0]," timetable");
                        $signal4 = stripos($tmp[0]," for location");
                        $signalX = strlen($tmp[0]);
                        if ($signal2 != FALSE){
                            $signalX = $signal2;
                        }
                        if ($signal3 != FALSE){
                            $signalX = $signal3;
                        }
                        if ($signal4 != FALSE){
                            $signalX = $signal4;
                        }
                        $length = $signalX - $signal;
                        $tmpCourse = substr($tmp[0],$signal,$length);
                        $signalT = strpos($tmpCourse,"to ") + 3;
                        $tmpFinal = "SPECIAL".substr($tmpCourse,$signalT);
                        addData($dataBase,$tableName,2,array("courseName","courseLocation"),array($tmp[0],$tmpFinal));
                        echo "<br>$tmp[0]: $tmpFinal?";
                    }
                }
            }
        }
        fclose($file);
    }