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

    /**
     * 新規ユーザー登録のためのメソッド
     *
     * @param string $name
     * @param string $employNum
     * @param string $pass
     * @param string $mail
     * @param boolean $isBoss
     * @return void
     */
    public function createUser($name, $employNum, $pass, $mail, $isBoss=false)
    {
        if (!empty($name) && !empty($employNum) && !empty($pass) && !empty($mail)) {
            
            $mydbh = $this->dbh;
            $pass = password_hash($pass, PASSWORD_DEFAULT);
            $isAdmin = $isBoss ? 'true' : 'false';
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
     * SELECT文を発行するメソッド
     * ダミーデータ取得用
     *
     * @param string $sql
     * @return void
     */
    public function getData(){
        return $this->dbh;
        // $mydbh=$this->dbh;
        // $sql="SELECT * FROM attendances ORDER BY id ASC";

        // try {
        //     $stmt=$mydbh->prepare($sql);
        //     $stmt->execute();

        // }catch(PDOException $e){
        //     $e->getMessage();
        // }
    }
}

?>
