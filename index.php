<?php
    include 'function/getUserCalendar.php';
    include 'function/geneIcal.php';
    include 'function/fileControl.php';
    include 'function/dbfunction.php';


    $dataBase = "zephyr";
    $tableName = "account";

    if (isset($_GET['userid'])){
        $userId = $_GET['userid'];
        if (!preg_match('|^\d{8}$|',$abc)) {
            $isAlreadyExist = searchData($dataBase,$tableName,1,array(),array("username"),array($userId));
            if ($isAlreadyExist[0][0] == 0){
                addData($dataBase,$tableName,1,array("username"),array($userId));
                $status = writeNewUserArrayToFile($userId);
                if ($status == "success"){
                    $resultArray = getUserFileData($userId);
                    $vCalendar = geneCal($resultArray[1]);
                    header('Content-Type: text/calendar; charset=utf-8');
                    header('Content-Disposition: attachment; filename="Calendar'.$userId.'.ics"');
                    echo $vCalendar->render();
                }
            }else{
                if ($isAlreadyExist[1][2] != ""){
                    $resultArray = getUserFileData($userId);
                    $vCalendar = geneCal($resultArray[1]);
                    header('Content-Type: text/calendar; charset=utf-8');
                    header('Content-Disposition: attachment; filename="Calendar'.$userId.'.ics"');
                    echo $vCalendar->render();
                }else{
                    $userInfo = analyseHeader(getOriginData($userId));
                    updateData($dataBase,$tableName,"userinfo",$userInfo,1,array(),array("userid"),$userId);
                    $resultArray = getUserFileData($userId);
                    $vCalendar = geneCal($resultArray[1]);
                    header('Content-Type: text/calendar; charset=utf-8');
                    header('Content-Disposition: attachment; filename="Calendar'.$userId.'.ics"');
                    echo $vCalendar->render();
                }
            }
        }else{
            echo "PLEASE CHECK IF YOUR STUDENT ID IS CORRECT";
        }
    }else{
        include_once "html/index.html";
    }


?>