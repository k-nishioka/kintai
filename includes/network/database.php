<?php

class Database
{

    private $dbh;

    const DB_NAME = 'attendance_management';
    const HOST = 'localhost';
    const USER = 'root';
    const PASS = 'root';

    /**
     * インスタンス生成時に呼ばれる初期化メソッド
     * データベースに接続するための関数
     */
    public function __construct()
    {
        $dns = "mysql:dbname=" . self::DB_NAME . ";host=" . self::HOST . ";charset=utf8";

        try {
            $pdo = new PDO($dns, self::USER, self::PASS);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            die();
        }

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dbh = $pdo;
    }








}

?>
