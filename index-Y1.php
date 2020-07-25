<?php
    ignore_user_abort(TRUE);
    include 'function/getUserCalendar.php';
    include 'function/geneIcal.php';
    include 'function/fileControl.php';
    include 'function/dbfunction.php';

    include 'config/setup_config.php';
    //$dataBase = "zephyr2020spring";
    $tableName = "account";

    if (isset($_GET['userid'])){
        $userId = $_GET['userid'];
        if (preg_match('/[A,B,C][-][0-9]{2}$/',$userId)) {
            $isAlreadyExist = searchData($dataBase,$tableName,1,array(),array("username"),array($userId));
            if (isset($_GET['ignoreLocal'])){
                $key = $_GET['ignoreLocal'];
                $status = writeNewY1UserArrayToFile($userId,$searchUrl_Y1);
                if ($status[0] == "success"){
                    $resultArray = getUserFileData($userId);
                    if (isset($_GET['notifytime'])){
                        $notifyTime = $_GET['notifytime'];
                        $vCalendar = geneCalWithAlarm($resultArray[1],$notifyTime);
                    }else{
                        $vCalendar = geneCal($resultArray[1]);
                    }
                    header('Content-Type: text/calendar; charset=utf-8');
                    header('Content-Disposition: attachment; filename="Calendar'.$userId.'.ics"');
                    echo $vCalendar->render();
                }
            }else{
                if ($isAlreadyExist[0][0] == 0){
                    addData($dataBase,$tableName,1,array("username"),array($userId));
                    $status = writeNewY1UserArrayToFile($userId);
                    if ($status[0] == "success"){
                        $resultArray = getUserFileData($userId);
                        if (isset($_GET['notifytime'])){
                            $notifyTime = $_GET['notifytime'];
                            $vCalendar = geneCalWithAlarm($resultArray[1],$notifyTime);
                        }else{
                            $vCalendar = geneCal($resultArray[1]);
                        }
                        header('Content-Type: text/calendar; charset=utf-8');
                        header('Content-Disposition: attachment; filename="Calendar'.$userId.'.ics"');
                        echo $vCalendar->render();
                    }
                }else{
                    if ($isAlreadyExist[1][2] != ""){
                        $resultArray = getUserFileData($userId);
                        if (isset($_GET['notifytime'])){
                            $notifyTime = $_GET['notifytime'];
                            $vCalendar = geneCalWithAlarm($resultArray[1],$notifyTime);
                        }else{
                            $vCalendar = geneCal($resultArray[1]);
                        }
                        header('Content-Type: text/calendar; charset=utf-8');
                        header('Content-Disposition: attachment; filename="Calendar'.$userId.'.ics"');
                        echo $vCalendar->render();
                    }else{
                        $userInfo = analyseHeader(getOriginData($userId));
                        updateData($dataBase,$tableName,"userinfo",$userInfo,1,array(),array("userid"),$userId);
                        $resultArray = getUserFileData($userId);
                        if (isset($_GET['notifytime'])){
                            $notifyTime = $_GET['notifytime'];
                            $vCalendar = geneCalWithAlarm($resultArray[1],$notifyTime);
                        }else{
                            $vCalendar = geneCal($resultArray[1]);
                        }
                        header('Content-Type: text/calendar; charset=utf-8');
                        header('Content-Disposition: attachment; filename="Calendar'.$userId.'.ics"');
                        echo $vCalendar->render();
                    }
                }
            }
        }else{
            include_once "html/wrong.html";
        }
    }else{
        include_once "html/indexY1.html";
    }


?>