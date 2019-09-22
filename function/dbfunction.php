<?php
    // 本函数组包含所有的数据库控制程序，主要用于框架的数据库管理功能
    include_once 'DBConnection.php';

    function getDBName(){
        include "../config/dbconfig/maindb.php";
        $CONNECTION =mysqli_connect($host,$user,$pass);
        $sqlCode = "SHOW DATABASES;";
        $result = mysqli_query($CONNECTION,$sqlCode);
        $finalResult = array();
        while ($row=mysqli_fetch_row($result)){
            array_push($finalResult,$row);
        }
        return $finalResult;
    }

    function createDB($dbName){
        include "../config/dbconfig/maindb.php";
        $CONNECTION =mysqli_connect($host,$user,$pass);
        $sqlCode = "CREATE DATABASE $dbName;";
        if ( mysqli_query($CONNECTION,$sqlCode)){
            return "SUCCESS";
        } else {
            return mysqli_error($CONNECTION);
        }
    }

    function getTableName($dataBase){
        $connectDBS = new connectDataBase($dataBase);
        $sqlCode = "SHOW TABLES;";
        $result = mysqli_query($connectDBS->link,$sqlCode);
        $finalResult = array();
        while ($row=mysqli_fetch_row($result)){
            array_push($finalResult,$row);
        }
        return $finalResult;
    }

    function createTable($dataBase,$tableName,$keyNum,$key,$keyType,$typeLength,$keyFeatureNum,$keyFeature,$priKey){
        $connectDBS = new connectDataBase($dataBase);
        $featureCounter = 0;
        $sqlCode = "CREATE TABLE $tableName (";
        for ($i=0;$i<$keyNum;$i++){
            $sqlCode = $sqlCode."$key[$i] $keyType[$i]";
            if ($keyType[$i] == 'varchar'){
                $sqlCode = $sqlCode."($typeLength[$i])";
            }
            if ($key[$i] == $priKey){
                $sqlCode = $sqlCode." PRIMARY KEY ";
            }
            if ($keyFeatureNum[$i] != 0){
                if ($keyFeatureNum[$i] == 1){
                    $sqlCode = $sqlCode." ".$keyFeature[$featureCounter];
                    $featureCounter = $featureCounter + 1;
                }else{
                    for ($j=$featureCounter;$j<($featureCounter+$keyFeatureNum[$i]);$j++){
                        $sqlCode = $sqlCode." ".$keyFeature[$j];
                    }
                    $featureCounter = $featureCounter + $keyFeatureNum[$i];
                }
            }
            if ($i <> ($keyNum-1)){
                $sqlCode = $sqlCode.",";
            }
        }
        $sqlCode = $sqlCode.")ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        if (mysqli_query($connectDBS->link,$sqlCode) == TRUE){
            return "SUCCESS";
        }else{
            return "FAILED";
        }
    }
    
    function deleteTable($dataBase,$tableName){
        $connectDBS = new connectDataBase($dataBase);
        $sqlCode = "DROP TABLE $tableName;";
        if (mysqli_query($connectDBS->link,$sqlCode) == TRUE){
            return "SUCCESS";
        }else{
            return "FAILED";
        }
    }

    function getColumnName($dataBase,$tableName){
        $connectDBS = new connectDataBase($dataBase);
        $sqlCode = "SHOW COLUMNS FROM $tableName;";
        $result = mysqli_query($connectDBS->link,$sqlCode);
        $finalResult = array();
        while ($row=mysqli_fetch_row($result)){
            array_push($finalResult,$row);
        }
        return $finalResult;
    }

    function addColumn($dataBase,$tableName,$keyName,$keyType,$keyLength){
        $connectDBS = new connectDataBase($dataBase);
        $sqlCode = "ALTER TABLE $tableName ADD ";
        $sqlCode = $sqlCode."$keyName $keyType";
        if ($keyType == "varchar"){
            $sqlCode = $sqlCode."($keyLength);";
        }
        if (mysqli_query($connectDBS->link,$sqlCode) == TRUE){
            return "SUCCESS";
        }else{
            return "FAILED";
        }
    }

    function deleteColumn($dataBase,$tableName,$keyName){
        $connectDBS = new connectDataBase($dataBase);
        $sqlCode = "ALTER TABLE $tableName DROP ";
        $sqlCode = $sqlCode."$keyName;";
        if (mysqli_query($connectDBS->link,$sqlCode) == TRUE){
            return "SUCCESS";
        }else{
            return "FAILED";
        }
    }

    function searchData($dataBase,$tableName,$keyNum,$keyFeature,$key,$keyValue){
        $connectDBS = new connectDataBase($dataBase);
        $sqlCode = "SELECT * FROM $tableName";
        if ($keyNum == 0){
            $result = mysqli_query($connectDBS->link,$sqlCode);
        }else{
            if ($keyNum == 1){
                $sqlCode = $sqlCode." WHERE $key[0] = '$keyValue[0]';";
                $result = mysqli_query($connectDBS->link,$sqlCode);
            }else{
                if ($keyFeature[0] == '0'){
                    $sqlCode = $sqlCode." WHERE $key[0] = '$keyValue[0]'";
                    $signCheck = 0;
                }else{
                    $sqlCode = $sqlCode." WHERE ($key[0] = '$keyValue[0]'";
                    $signCheck = 1;
                }
                for ($i=1;$i<$keyNum;$i++){
                    switch ($keyFeature[$i]){
                        case "0":
                            $sqlCode = $sqlCode." AND";
                            break;
                        case "1":
                            $sqlCode = $sqlCode." OR";
                            break;
                        case "2":
                            $sqlCode = $sqlCode.") AND";
                            $signCheck = $signCheck - 1;
                            break;
                        case "3":
                            $sqlCode = $sqlCode.") OR";
                            $signCheck = $signCheck - 1;
                            break;
                        case "4":
                            $sqlCode = $sqlCode." AND (";
                            $signCheck = $signCheck + 1;
                            break;
                        case "5":
                            $sqlCode = $sqlCode." OR (";
                            $signCheck = $signCheck + 1;
                            break;
                    }
                    $sqlCode = $sqlCode." $key[$i] = '$keyValue[$i]'";
                }
                $sqlCode = $sqlCode.";";
                $result = mysqli_query($connectDBS->link,$sqlCode);
            }
        }
        $rowCount=mysqli_num_rows($result);
        $finalResult = array(array($rowCount));
        while ($row=mysqli_fetch_row($result)){
            array_push($finalResult,$row);
        }
        return $finalResult;
    }

    function updateData($dataBase,$tableName,$changeKey,$changeValue,$keyNum,$keyFeature,$key,$keyValue){
        $connectDBS = new connectDataBase($dataBase);
        $sqlCode = "UPDATE $tableName SET";
        $sqlCode = $sqlCode." $changeKey = '$changeValue' ";
        if ($keyNum == 0){
        }else{
            if ($keyNum == 1){
                $sqlCode = $sqlCode." WHERE $key[0] = '$keyValue[0]';";
            }else{
                if ($keyFeature[0] == '0'){
                    $sqlCode = $sqlCode." WHERE $key[0] = '$keyValue[0]'";
                    $signCheck = 0;
                }else{
                    $sqlCode = $sqlCode." WHERE ($key[0] = '$keyValue[0]'";
                    $signCheck = 1;
                }
                for ($i=1;$i<$keyNum;$i++){
                    switch ($keyFeature[$i]){
                        case "0":
                            $sqlCode = $sqlCode." AND";
                            break;
                        case "1":
                            $sqlCode = $sqlCode." OR";
                            break;
                        case "2":
                            $sqlCode = $sqlCode.") AND";
                            $signCheck = $signCheck - 1;
                            break;
                        case "3":
                            $sqlCode = $sqlCode.") OR";
                            $signCheck = $signCheck - 1;
                            break;
                        case "4":
                            $sqlCode = $sqlCode." AND (";
                            $signCheck = $signCheck + 1;
                            break;
                        case "5":
                            $sqlCode = $sqlCode." OR (";
                            $signCheck = $signCheck + 1;
                            break;
                    }
                    $sqlCode = $sqlCode." $key[$i] = '$keyValue[$i]'";
                }
                $sqlCode = $sqlCode.";";
            }
        }
        if (mysqli_query($connectDBS->link,$sqlCode) == TRUE){
            return "SUCCESS";
        }else{
            return "FAILED";
        }
    }

    function addData($dataBase,$tableName,$keyNum,$keyName,$keyValue){
        $connectDBS = new connectDataBase($dataBase);
        $sqlCode = "INSERT INTO $tableName (";
        for ($i=0;$i<$keyNum;$i++){
            $sqlCode = $sqlCode."$keyName[$i]";
            if ($i <> ($keyNum-1)){
                $sqlCode = $sqlCode.",";
            }else{
                $sqlCode = $sqlCode.") VALUES ('";
            }
        }
        for ($i=0;$i<$keyNum;$i++){
            $sqlCode = $sqlCode."$keyValue[$i]";
            if ($i <> ($keyNum-1)){
                $sqlCode = $sqlCode."','";
            }else{
                $sqlCode = $sqlCode."');";
            }
        }
        if (mysqli_query($connectDBS->link,$sqlCode) == TRUE){
            return "SUCCESS";
        }else{
            return "FAILED";
        }
    }

    function deleteData($dataBase,$tableName,$keyNum,$keyFeature,$key,$keyValue){
        $connectDBS = new connectDataBase($dataBase);
        $sqlCode = "DELETE FROM $tableName";
        if ($keyNum == 0){
        }else{
            if ($keyNum == 1){
                $sqlCode = $sqlCode." WHERE $key[0] = '$keyValue[0]';";
            }else{
                if ($keyFeature[0] == '0'){
                    $sqlCode = $sqlCode." WHERE $key[0] = '$keyValue[0]'";
                    $signCheck = 0;
                }else{
                    $sqlCode = $sqlCode." WHERE ($key[0] = '$keyValue[0]'";
                    $signCheck = 1;
                }
                for ($i=1;$i<$keyNum;$i++){
                    switch ($keyFeature[$i]){
                        case "0":
                            $sqlCode = $sqlCode." AND";
                            break;
                        case "1":
                            $sqlCode = $sqlCode." OR";
                            break;
                        case "2":
                            $sqlCode = $sqlCode.") AND";
                            $signCheck = $signCheck - 1;
                            break;
                        case "3":
                            $sqlCode = $sqlCode.") OR";
                            $signCheck = $signCheck - 1;
                            break;
                        case "4":
                            $sqlCode = $sqlCode." AND (";
                            $signCheck = $signCheck + 1;
                            break;
                        case "5":
                            $sqlCode = $sqlCode." OR (";
                            $signCheck = $signCheck + 1;
                            break;
                    }
                    $sqlCode = $sqlCode." $key[$i] = '$keyValue[$i]'";
                }
                $sqlCode = $sqlCode.";";
            }
        }
        if (mysqli_query($connectDBS->link,$sqlCode) == TRUE){
            return "SUCCESS";
        }else{
            return "FAILED";
        }
    }
?>