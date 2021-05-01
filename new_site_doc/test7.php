<?php

$fh = fopen('stores.csv','rb');

$host = 'localhost';
$port = '3306';
$dbname = 'liuqcup';
$user = 'liuqcup';
$passwd = 'liuqcup$2021$kh';

try {
    $dbConn = new PDO("mysql:server=".$host.";dbname=".$dbname,$user,$passwd);
    printf("connection sucess");
} catch (Exception $ell) {
    printf($ell);
}

$runSQL = $dbConn->prepare("INSERT INTO stores(storeid,storename,businessid,qrcodeid,address)
                                        values (?,?,?,?,?)");

while((! feof($fh)) && ($info = fgetcsv($fh))){
   $sql[0] = strval($info[0]);
   $sql[1] = strval($info[1]);
   $sql[2] = strval(trim($info[7]));
   $sql[3] = strval(trim($info[7]));
   $sql[4] = strval($info[13]);
   $runSQL->execute($sql);

    print "$info[1] wirte\n";
}

fclose($fh);
?>
