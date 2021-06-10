<?php
header("Content-Type:text/html; charset=utf-8");
$fh = fopen('stores.csv','rb');

$host = 'localhost';
$port = '3306';
$dbname = 'liuqcup';
$user = 'liuqcup';
$passwd = 'liuqcup$2021$kh';

try {
    $dbConn = new PDO("mysql:server=".$host.";dbname=".$dbname.";charset=utf8",$user,$passwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    printf("connection sucess");
} catch (Exception $ell) {
    printf($ell);
}

$sqlstmt = 'UPDATE stores SET storename= :name , address= :addr WHERE storeid = :id';

$runSQL = $dbConn->prepare($sqlstmt, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));  

while((! feof($fh)) && ($info = fgetcsv($fh))){
   //$sql = array($info[1],$info[13],$info[7]);
   $runSQL->execute(array(':name' => $info[1], ':addr' => $info[13], ':id' => $info[7]));
   $runSQL->fetchAll();
   // var_dump($sql);
   print "$info[1] update\n";
}

fclose($fh);
?>
