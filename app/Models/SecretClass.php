<?php

namespace App\Models;

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
?>
