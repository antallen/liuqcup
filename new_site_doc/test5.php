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

$runSQL = $dbConn->prepare("SELECT * FROM iliuqcup.cadd01 ORDER BY cadd01 DESC");
$runSQL->execute();


$fh = fopen('members1.csv','wb');

while ($row = $runSQL->fetch(PDO::FETCH_NUM)){
    fputcsv($fh,$row);
    print "$row[0]\n";
}

fclose($fh);

?>
