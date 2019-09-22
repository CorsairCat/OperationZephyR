<?php
    include 'function/getUserCalendar.php';
    include 'function/geneIcal.php';
    include 'function/fileControl.php';
    include 'function/dbfunction.php';


    $dataBase = "zephyr";
    $tableName = "account";
    $userId = $_GET['userid'];
    $isAlreadyExist = searchData($dataBase,$tableName,1,array(),array("username"),array($userId));
    if ($isAlreadyExist[0][0] == 0){
        addData($dataBase,$tableName,1,array("username"),array($userId));
        $status = writeNewUserArrayToFile($userId);
        if ($status == "success"){
            $resultArray = getUserFileData($userId);
            $vCalendar = geneCal($resultArray[1]);
            header('Content-Type: text/calendar; charset=utf-8');
            header('Content-Disposition: attachment; filename="cal.ics"');
            echo $vCalendar->render();
        }
    }else{
        $resultArray = getUserFileData($userId);
        $vCalendar = geneCal($resultArray[1]);
        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="cal.ics"');
        echo $vCalendar->render();
    }


?>