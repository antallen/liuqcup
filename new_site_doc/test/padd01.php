<?php

$host = '148.72.232.167';
$port = '1433';
$dbname = 'iliuq_cup';
$user = 'iliuqcup';
$passwd = 'iliuq2019cup';

try {
    $dbConn = new PDO("sqlsrv:server=$host,$port;Database=$dbname",$user,$passwd);
    printf("connection sucess");
} catch (Exception $ell) {
    printf($ell);
}

$runSQL = $dbConn->prepare("SELECT * FROM iliuqcup.padd01 ORDER BY padd01");
$runSQL->execute();

$fh = fopen('padd01.csv','wb');

while ($row = $runSQL->fetch(PDO::FETCH_NUM)){
    fputcsv($fh,$row);
}

fclose($fh);
?>
