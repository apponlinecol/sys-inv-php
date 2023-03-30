<?php

class connections
{
    static public function connect(){

        $link = new PDO("mysql:host=localhost;dbname=apponlin_inv","root","", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"] );
//        $link = new PDO("mysql:host=localhost;dbname=natura97_dyc","natura97_system","-c!vPK$~CKv;", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"] );
        $link->exec("set names utf8");

        return $link;

    }
}