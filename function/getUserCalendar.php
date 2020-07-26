<?php
//初始函数，获取xtml数据
    function getOriginData($userId,$searchUrl_Array){
        //$searchUrl = "http://timetablingunnc.nottingham.ac.uk:8005/individual.htm;Student+Sets;id;".$userId."?template=Student+Set+Individual&weeks=23-40&days=&periods=&Width=0&Height=0&nsukey=arxaDi%2F5%2B1sCNvPOwHUckNKHjKJWh1qWVVhCHluhztAVUMZ5%2FNDkN1rIQETXdTuU796GaPBPto7q2mit6SVyNuwUOBTgvWuGlXnm%2FzJwHMwjwvc9m3RkbL%2BNVP0605I1Y32BX5E0sN3jLTkHBks1iQ%3D%3D";
        $searchUrl = $searchUrl_Array[0].$userId.$searchUrl_Array[1];
        header( "Content-type:text/html;Charset=utf-8" );  
        $curlObj = curl_init();
        curl_setopt ( $curlObj , CURLOPT_USERAGENT ,"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.113 Safari/537.36" );
        curl_setopt ($curlObj,CURLOPT_URL,$searchUrl);
        curl_setopt ($curlObj, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($curlObj);
        $finalResult = file_get_contents($searchUrl);
        return $finalResult;
    }
    function getOriginY1Data($teamId,$searchUrl_Array){
        //$searchUrl = "http://timetablingunnc.nottingham.ac.uk:8005/reporting/Individual;Student+Sets;name;Year%201-".$teamId."%20(Spring)?template=Student+Set+Individual&weeks=23-52&days=1-7&periods=";
        $searchUrl = $searchUrl_Array[0].$teamId.$searchUrl_Array[1];
        header( "Content-type:text/html;Charset=utf-8" );  
        $curlObj = curl_init();
        curl_setopt ( $curlObj , CURLOPT_USERAGENT ,"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.113 Safari/537.36" );
        curl_setopt ($curlObj,CURLOPT_URL,$searchUrl);
        curl_setopt ($curlObj, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($curlObj);
        $finalResult = file_get_contents($searchUrl);
        return $finalResult;
    }
//删除获取的课表中的无效部分
    function analyseData($dataString){
        $startId = strpos($dataString,"<body>") + 6;
        $endId = strpos($dataString,"</body>");
        $length = $endId - $startId;
        $fstString = substr($dataString,$startId,$length);
        return $fstString;
    }
//解析表头
    function analyseHeader($dataString){
        $startId = strpos($dataString,"<!-- START REPORT HEADER -->") + 28;
        $endId = strpos($dataString,"<!-- END REPORT HEADER -->");
        $length = $endId - $startId;
        $endString = strip_tags(substr($dataString,$startId,$length),"<table>");
        return $endString;
    }

//解析表身
    function analyseBody($dataString){
        $startId = strpos($dataString,"<!-- START ROW OUTPUT -->") + 25;
        $endId = strpos($dataString,"<!-- END ROW OUTPUT -->");
        $length = $endId - $startId;
        $endString = substr($dataString,$startId,$length);
        //strip_tags(substr($dataString,$startId,$length),"<tr>");
        return $endString;
    }


    //计算课程长度
    function getPlaceHolder($str){
        $signal = 'colspan=';
        if (strpos($str,$signal) != FALSE){
            $startId = strpos($str,$signal) + 9;
            $colspan = "";
            for ($i=$startId;$i<($startId+100);$i++){
                $tmp = $str[$i];
                if ($tmp != "'"){
                    $colspan = $colspan.$tmp;
                }else{
                    break;
                }
            }
            return array("success",$startId,intval($colspan));
        }else{
            return array("failed","Oops, Something went wrong!");
        }
    }

    //解析每个课程的时间长度
    function checkAllCourseSpan($strBody){
        $signal1 = getPlaceHolder($strBody);
        if ($signal1[0] == "success"){
            $colArray = array($signal1[2]);
            $signal = array("success");
            $strBody = substr($strBody,$signal1[1]); 
            for ($i=0;$i<100;$i++){
                $signal = getPlaceHolder($strBody);
                //echo $i;
                if ($signal[0] == "success"){
                    $strTmp = substr($strBody,$signal[1]); 
                    array_push($colArray,$signal[2]);
                    $strBody = $strTmp;
                }else{
                    break;
                }
            }
            //echo "success";
            //echo "<br>";
            //echo count($colArray);
            //echo "<br>";
            //for ($i=0;$i<count($colArray);$i++){
            //    echo $colArray[$i]."<br>";
            //}
            return array("success",$colArray);
        }
    }

    //解析并分离出每个课程的html数据
    function getCourseStr($dataString){
        $signal = '<!-- START OBJECT-CELL -->';
        if (strpos($dataString,$signal) != FALSE){
            $startId = strpos($dataString,$signal) + 26;
            $endId = strpos($dataString,"<!-- END OBJECT-CELL -->");
            $length = $endId - $startId;
            $courseStr = substr($dataString,$startId,$length);
            $endId = $endId + 24;
            $otherString = substr($dataString,$endId);
            $courseStr = strip_tags($courseStr,"<td>");
            $emptySpanStr = substr($dataString,0,($startId-26));
            $spanToFront = getEmptySpan($emptySpanStr);
            return array("success",$spanToFront,$courseStr,$otherString);
        }else{
            return array("failed","Oops, Something went wrong!");
        }
    }

    //获取一节课与前课之间的空课时
    function getEmptySpan($str){
        $str = strip_tags($str,"<tr>");
        $num = substr_count($str,"&nbsp;");
        return $num;
    }

    //解析内部信息，返回包含课程信息的数组
    function getCourseInfo($str){
        $inputArray = explode(">",$str);
        $infoArray = array();
        for ($i=0;$i<count($inputArray);$i++){
            if (($i%2) != 0){
                $tmp = substr($inputArray[$i],0,strpos($inputArray[$i], '<')); //删除剩下的</td部分；
                array_push($infoArray,$tmp);
            }
        }
        include 'config/setup_config.php'; //引入全局配置文件
        //$dataBase = "zephyr2020spring";
        $weekStr = $infoArray[4];
        array_pop ($infoArray);
        $weekArray = getWeekList($weekStr);
        array_push($infoArray,$weekArray);
        if ($infoArray[2] == ""){
            $tableName1 = "speciallocation";
            $result = searchData($dataBase,$tableName1,1,array(),array("courseName"),array($infoArray[0]));
            if ($result[0][0] != 0){
                $signalX = stripos($result[1][2],"PECIAL");
                if ($signalX != FALSE){
                    $sameCourseName = substr($result[1][2],7);
                    $result2 = searchData($dataBase,$tableName1,1,array(),array("courseName"),array($sameCourseName));
                    //echo $sameCourseName."<br>";
                    if ($result2[0][0] != 0){
                        $infoArray[2] = $result2[1][2];
                    }else{
                        $infoArray[2] = $sameCourseName;
                    }
                }else{
                    //echo "no special <br>";
                    $infoArray[2] = $result[1][2];
                }
            }else{
                //echo "no record <br>";
            }
        }else{
        }
        $tableName = "course";
        $infoArray[0] = preg_replace ("/\s(?=\s)/","", $infoArray[0]);
        $result = searchData($dataBase,$tableName,1,array(),array("courseid"),array($infoArray[0]));
        if ($result[0][0] != 0){
            $infoArray[0] = $result[1][2];
            array_push($infoArray,$result[1][3]);
        }else{
            array_push($infoArray,"unknown");
        }
        //echo print_r($result);
        return $infoArray;
    }

    //按周内七日进行拆分数据
    function getEveryDay($str){
        $days = array(">Mon<",">Tue<",">Wed<",">Thu<",">Fri<",">Sat<",">Sun<","<!-- END ROW OUTPUT -->"); // 预定义每周数据
        $dayPos = array();
        for ($i=0;$i<8;$i++){
            $tmp = strpos($str,$days[$i]);
            array_push($dayPos,$tmp);
        }
        $dayData = array();
        for ($i=0;$i<7;$i++){
            $tmp = substr($str,$dayPos[$i],($dayPos[$i+1]-$dayPos[$i]));
            array_push($dayData,$tmp);
        }
        return $dayData;
    }

    function calcuTime($num){
        $realTimePos = 8 + ($num%28) * 0.5;
        return $realTimePos;
    }

    function getWeekList($weekStr){
        $weekGenerArray = explode(",",$weekStr);
        $num = count($weekGenerArray);
        $weekArray = array();
        for ($i=0;$i<$num;$i++){
            $signal = strpos($weekGenerArray[$i],"-");
            if ($signal != false){
                $tmpArray = explode("-",$weekGenerArray[$i]);
                for ($j=intval($tmpArray[0]);$j<=intval($tmpArray[1]);$j++){
                    array_push($weekArray,$j);
                }
            }else{
                array_push($weekArray,intval($weekGenerArray[$i]));
            }
        }
        return $weekArray;
    }

    //解析其中一天的数据
    function analyseDayCourse($str){
        $oneDayLong = 28;
        $courseSpanArray = checkAllCourseSpan($str);
        $courseInfoArray = array();
        $beforeCourseSpan = array();
        $tmp = getCourseStr($str);
        while ( $tmp[0] == "success" ){
            $infoTmp = getCourseInfo($tmp[2]);
            array_push($courseInfoArray,$infoTmp);
            array_push($beforeCourseSpan,$tmp[1]);
            $tmpStay = $tmp[3];
            $tmp = getCourseStr($tmp[3]);
        }
        $courseNum = count($courseInfoArray);
        $timeCounter = 0;
        for ($i=0;$i<$courseNum;$i++){
            $timeCounter = $timeCounter + $beforeCourseSpan[$i];
            $courseStartTmp = calcuTime($timeCounter);
            $timeCounter = $timeCounter + $courseSpanArray[1][$i];
            $courseEndTmp = calcuTime($timeCounter);
            array_push($courseInfoArray[$i],$courseStartTmp,$courseEndTmp);
        }
        return $courseInfoArray;
    }

    function getClassInfoToArray($userId,$searchUrl){
        $str = getOriginData($userId,$searchUrl);
        $dayStr = getEveryDay($str);
        $headerInfo = analyseHeader($str);
        $courseInfo = array();
        for ($k=0;$k<7;$k++){
            $dayInfo = analyseDayCourse($dayStr[$k]);
            array_push($courseInfo,$dayInfo);
        }
        return array("success",$headerInfo,$courseInfo);
    }

    function getY1ClassInfoToArray($teamId,$searchUrl){
        $str = getOriginY1Data($teamId,$searchUrl);
        $dayStr = getEveryDay($str);
        $headerInfo = analyseHeader($str);
        $courseInfo = array();
        for ($k=0;$k<7;$k++){
            $dayInfo = analyseDayCourse($dayStr[$k]);
            array_push($courseInfo,$dayInfo);
        }
        return array("success",$headerInfo,$courseInfo);
    }
?>