<?php
    include 'function/getUserCalendar.php';
    include 'function/geneIcal.php';
    $userId = $_GET['userid'];
    $result = getClassInfoToArray($userId);
    if ($result[0] == "success"){
        $vCalendar = geneCal($result[2]);
        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="cal.ics"');
        // 5. Output
        echo $vCalendar->render();
    }
?>