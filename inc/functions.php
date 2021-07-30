<?php
//XSS対策用関数
function str2html(string $string) :string {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

//データベース接続処理用関数
function db_open() :PDO {
    $user = "hogeUser";
    $password = "hogehoge";
//$optの中身はテキスト192～193に記載
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
    ];
//PDOでの接続
    $dbh = new PDO('mysql:host=localhost;dbname=highschool_searcher_db', $user, $password, $opt);
    return $dbh;
}