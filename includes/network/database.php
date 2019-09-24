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
        $dns = "mysql:dbname=" . self::DB_NAME . ";host=" . self::HOST . ";charset=utf8mb4";

        try {
            $pdo = new PDO($dns, self::USER, self::PASS);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            die();
        }

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dbh = $pdo;
    }

    /**
     * 新規ユーザー登録のためのメソッド
     *
     * @param string $name
     * @param string $employNum
     * @param string $pass
     * @param string $mail
     * @param boolean $isBoss（管理者になるユーザーの確認）
     * @return void
     */
    public function createUser($name, $employNum, $pass, $mail, $isAdmin=false)
    {
        if (!empty($name) && !empty($employNum) && !empty($pass) && !empty($mail)) {

            $mydbh = $this->dbh;
            $pass = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `users` (name, employee_num, mail, pass, is_admin) VALUE (?, ?, ?, ?, ?)";

            try {
                $prepare = $mydbh->prepare($sql);
                $prepare->bindValue(1, (string)$name, PDO::PARAM_STR);
                $prepare->bindValue(2, (int)$employNum, PDO::PARAM_INT);
                $prepare->bindValue(3, (string)$mail, PDO::PARAM_STR);
                $prepare->bindValue(4, (string)$pass, PDO::PARAM_STR);
                $prepare->bindValue(5, (bool)$isAdmin, PDO::PARAM_BOOL);
                $prepare->execute();
            } catch (PDOException $e) {
                // TODO: エラーをログに出力できるようにしたい
                echo 'Error: ' . $e->getMessage();
                die();
            }
        }
    }

    /**
     * 全管理者ユーザーを取得するメソッド
     *
     * @return array 多次元配列
     */
    public function getAdminUsers()
    {
        $mydbh = $this->dbh;
        $sql = "SELECT * FROM `users` WHERE is_admin = true";
        $prepare = $mydbh->query($sql);
        $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * 名前とメールアドレスからユーザーを取得するメソッド
     *
     * @param string $name
     * @param string $mail
     * @return array
     */
    public function getUserFrom($name, $mail)
    {
        $mydbh = $this->dbh;
        $sql = "SELECT * FROM `users` WHERE name = ? AND mail = ?";

        try {
            $prepare = $mydbh->prepare($sql);
            $prepare->bindValue(1, (string)$name, PDO::PARAM_STR);
            $prepare->bindValue(2, (string)$mail, PDO::PARAM_STR);
            $prepare->execute();
            $result = $prepare->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // TODO: エラーをログに出力できるようにしたい
            echo 'Error: ' . $e->getMessage();
            die();
        }
        return $result;
    }

    /**
     * 管理あyユーザーと一般ユーザーの繋がり中間テーブルの作成メソッド
     *
     * @param int $userId
     * @param int $bossId
     * @return void
     */
    public function createHierarchicalRelationships($userId, $bossId)
    {
        if (!empty($userId) && !empty($bossId)) {

            $mydbh = $this->dbh;
            $sql = "INSERT INTO `hierarchical_relationships` (boss_user_id, subordinate_user_id) VALUE (?, ?)";

            try {
                $prepare = $mydbh->prepare($sql);
                $prepare->bindValue(1, (int)$bossId, PDO::PARAM_INT);
                $prepare->bindValue(2, (int)$userId, PDO::PARAM_INT);
                $prepare->execute();
            } catch (PDOException $e) {
                // TODO: エラーをログに出力できるようにしたい
                echo 'Error: ' . $e->getMessage();
                die();
            }
        }
    }












}

?>
