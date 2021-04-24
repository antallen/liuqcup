<?php

$fh = fopen('stores.csv','rb');

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

$runSQL = $dbConn->prepare("INSERT INTO iliuqcup.padd01 (
                   padd01,padd02,padd03,padd04,padd05,padd06,padd07,
                   padd08,padd09,padd10,padd11,padd12,padd13,padd14,
                   padd15,padd16,padd17,padd18,padd19)
                   VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

while ((! feof($fh)) && ($info = fgetcsv($fh))){
    $runSQL->execute($info);
    print "Inserted $info[0]\n";
}
fclose($fh);
?>
