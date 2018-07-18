<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
$_POST['year']="2017";
if (isset($_POST['year'])&&strlen(htmlspecialchars($_POST['year']))===4){
    $year = htmlspecialchars($_POST['year']);
    $query = "SELECT * FROM almanax ORDER BY `date` ASC";
    $results = sqldb::safesql($query);
    $ical = "BEGIN:VCALENDAR\nVERSION:2.0\nPRODID:-//Profus.ovh//NONSGML Almanax//FR\nCALSCALE:GREGORIAN\nMETHOD:PUBLISH\n";
    foreach ($results as $key=>$value){
        $ical = $ical."BEGIN:VEVENT
DTSTART:".date('Ymd',strtotime(str_replace('2016',$year,$results[$key]['date'])))."T010000Z
DESCRIPTION:".$results[$key]['qty']." ".$results[$key]['item']."
SUMMARY:Almanax ".$results[$key]['qty']." ".$results[$key]['item']."
TRANSP:TRANSPARENT
END:VEVENT\n";
    }
    $ical = $ical.PHP_EOL."END:VCALENDAR";
    file_put_contents($_SERVER['DOCUMENT_ROOT']."/downloads/$year.ics",$ical,LOCK_EX);
}