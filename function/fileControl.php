<?php
    function writeNewUserArrayToFile($userId){
        $filePath = 'storage/'.$userId.'.txt';
        $fileHandle = fopen($filePath,"w") or die("Unable to open file!");
        $userData = getClassInfoToArray($userId);
        if ($userData[0] == "success"){
            $fileData = array($userData[1],$userData[2]);
            $serialized_data = serialize($fileData);
            $signal = file_put_contents($filePath, $serialized_data);
            fclose($fileHandle);
            if ($signal != FALSE){
                return array("success");
            }else{
                return array("error","file_write_error");
            }
        }else{
            return array("error","Oops,something_went_wrong");
        }
    }

    function getUserFileData($userId){
        $filePath = 'storage/'.$userId.'.txt'; 
        $fileHandle = fopen($filePath,"r") or die("Unable to open file!");
        $cacheArray=unserialize(file_get_contents($filePath));
        return $cacheArray;
    }
?>