<?php
ini_set('default_charset', 'utf8');

class SecretClass {
    public static function generateSalt($numAlpha=8,$numNonAlpha=2)
    {
        $listAlpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $listNonAlpha = ',;:!?.$/*-+&@_+;./*&?$-!,';

        return str_shuffle(
            substr(str_shuffle($listAlpha),0,$numAlpha) .
            substr(str_shuffle($listNonAlpha),0,$numNonAlpha)
            );
    }

    public static function generateToken($salt,$password){
        $token = password_hash($password.$salt,PASSWORD_BCRYPT);
        return $token;
    }
}


$date = strval(date("YmdHis"));


//資料庫的連結
$host = '127.0.0.1';
$port = '3306';
$dbname = 'liuqcup';
$user = 'liuqcup';
$passwd = 'liuqcup$2021$kh';

try {
    $dbConn = new PDO('mysql:host='.$host.';dbname='.$dbname,$user,$passwd);
    printf("connection sucess");
} catch (Exception $ell) {
    printf($ell);
}

$runSQL = $dbConn->prepare('INSERT INTO customers(cusid,cusname,cusphone,email,salt,token,`password`) VALUES (?,?,?,?,?,?,?)');

//從CSV檔案讀取資料進來
$csv = fopen('../test/cadd01.csv','rb');

//列出檔案內容
while ((! feof($csv)) && ($line = fgetcsv($csv))){
    if (strlen(strval(trim($line[0]))) ==10)
    {
        //print (strval(trim($line[0])));
        $cusphone = (strval(trim($line[0])));
    } else {
        continue;
    }
    if (!empty(strval(trim($line[1])))){
        print strval(trim($line[1]));
        $cusname = strval(trim($line[1]));
    } else {
        print "空白";
        $cusname = "";
    }
    if (!empty(strval(trim($line[2])))){
        print strval(trim($line[2]));
        $email = strval(trim($line[2]));
    }else {
        print "空白";
        $email = "";
    }
    if (!empty(strval(trim($line[5])))){
        print strval(trim($line[5]));
        $rand = strval(rand(0,100));
        $cusid = "CUS".strval(trim($line[5])).$rand;
    }else {
        print "空白";
    }
    $auths = new SecretClass();
    $salt = $auths->generateSalt();
    $password = "ABC123";
    $token = $auths->generateToken($salt,$password);

    $runSQL->execute(array($cusid,$cusname,$cusphone,$email,$salt,$token,$password));
    $runSQL->fetchAll();
    printf("新增成功\n");
}

//關閉檔案
fclose($csv);
