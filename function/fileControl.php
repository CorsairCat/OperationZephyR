<?php
    function writeNewUserArrayToFile($userId){
        $filePath = $file='storage/'.$userId.'.config'; 
        $fileHandle = fopen($filePath,"w");
        $userData = getClassInfoToArray($userId);
        if ($userData[0] == "success"){
            $fileData = array($userData[1],$userData[2]);
            $serialized_data = serialize($fileData);
            $signal = file_put_contents($filePath, $serialized_data);
            fclose($file);
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
        $filePath = $file='storage/'.$userId.'.config'; 
        $fileHandle = fopen($filePath,"r");
        $cacheArray=unserialize(fread($handle,filesize($file)));
        return $cacheArray;
    }
?>