<?php

$host = '103.134.82.178';
$port = '51433';
$dbname = 'iliuq_cup';
$user = 'sa';
$passwd = 'Franco@2021';

try {
    $dbConn = new PDO("sqlsrv:server=$host,$port;Database=$dbname",$user,$passwd);
    printf("connection sucess");
} catch (Exception $ell) {
    printf($ell);
}

$runSQL = $dbConn->prepare("SELECT * FROM iliuqcup.padd01 WHERE padd01 between '13354461' and '13354475'");
$runSQL->execute();

$fh = fopen('stores.csv','wb');

while ($row = $runSQL->fetch(PDO::FETCH_NUM)){
    fputcsv($fh,$row);
}

fclose($fh);
?>
