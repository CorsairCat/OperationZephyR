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
    $dataBase = "zephyr";
    $tableName = "course";
    for ($i=0;$i<count($fileArray);$i++){
        $file = fopen("stable/".$fileArray[$i],"r");
        while(! feof($file)){
            $tmp = fgetcsv($file);
            if ($tmp != NULL){
                if ($tmp[2] != "" AND $tmp[2] != "Name of Type"){
                    array_push($couserArray,$tmp);
                    addData($dataBase,$tableName,3,array("courseid","coursename","coursetype"),array($tmp[0],$tmp[1],$tmp[2]));
                }
            }
        }
        fclose($file);
    }
    echo print_r($couserArray);