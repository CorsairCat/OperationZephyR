<?php
    ignore_user_abort(TRUE);
    include 'function/getUserCalendar.php';
    include 'function/geneIcal.php';
    include 'function/fileControl.php';
    include 'function/dbfunction.php';


    $dataBase = "zephyr";
    $tableName = "account";

    if (isset($_GET['userid'])){
        $userId = $_GET['userid'];
        if (preg_match('/\d{8}$/',$userId)) {
            if (preg_match('/[2][0][2][1]\d{4}$/',$userId)){
                echo "<a href='index-Y1.php'>Y1 please use this link<a>";
            }else{
                $isAlreadyExist = searchData($dataBase,$tableName,1,array(),array("username"),array($userId));
                if (isset($_GET['ignoreLocal'])){
                    $key = $_GET['ignoreLocal'];
                    $status = writeNewUserArrayToFile($userId);
                    if ($status[0] == "success"){
                        $resultArray = getUserFileData($userId);
                        $vCalendar = geneCal($resultArray[1]);
                        header('Content-Type: text/calendar; charset=utf-8');
                        header('Content-Disposition: attachment; filename="Calendar'.$userId.'.ics"');
                        echo $vCalendar->render();
                    }
                }else{
                    if ($isAlreadyExist[0][0] == 0){
                        addData($dataBase,$tableName,1,array("username"),array($userId));
                        $status = writeNewUserArrayToFile($userId);
                        if ($status[0] == "success"){
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
                }
            }
        }else{
            include_once "html/wrong.html";
        }
    }else{
        include_once "html/index.html";
    }


?>