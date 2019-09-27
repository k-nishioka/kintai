<?php

require_once(dirname(__FILE__) . "/../function.php");

class Database
{

    private $dbh;

    const DB_NAME = 'attendance_management';
    const HOST = 'localhost';
    const USER = 'root';
    const PASS = 'root';
    static $mail = "";
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
     * 全ユーザーを取得するメソッド
     *
     * @return array 二次元配列
     */
    public function getUsers()
    {
        $mydbh = $this->dbh;
        $sql = "SELECT * FROM `users`";
        $prepare = $mydbh->query($sql);
        $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * 全管理者ユーザーを取得するメソッド
     *
     * @return array 二次元配列
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
     * メールアドレスからユーザーを取得するメソッド
     *
     * @param string $mail
     * @return array
     */
    public function getUserFrom($mail)
    {
        $mydbh = $this->dbh;
        $sql = "SELECT * FROM `users` WHERE mail = ?";

        try {
            $prepare = $mydbh->prepare($sql);
            $prepare->bindValue(1, (string)$mail, PDO::PARAM_STR);
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
     * ユーザーIDからユーザーを取得するメソッド
     *
     * @param int $id
     * @return array
     */
    public function getUserBy($id)
    {
        $mydbh = $this->dbh;
        $sql = "SELECT * FROM `users` WHERE id = ?";

        try {
            $prepare = $mydbh->prepare($sql);
            $prepare->bindValue(1, (int)$id, PDO::PARAM_INT);
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

    /**
     * 新規の出勤を作成するメソッド
     *
     * @param string $day
     * @param string $hour
     * @param string $minute
     * @param int $businessType
     * @param int $userId
     * @return void
     */
    public function createAttendance($day, $hour, $minute, $businessType, $userId)
    {
        if (!empty($day) && !empty($hour) && !empty($minute) && !empty($businessType)) {

            $mydbh = $this->dbh;
            $date = $day . " " . $hour . ":" . $minute . ":00"; 
            $sql = "INSERT INTO `attendances` (start_time, business_type_id, user_id) VALUE (?, ?, ?)";

            try {
                $prepare = $mydbh->prepare($sql);
                $prepare->bindValue(1, (string)$date, PDO::PARAM_STR);
                $prepare->bindValue(2, (int)$businessType, PDO::PARAM_INT);
                $prepare->bindValue(3, (int)$userId, PDO::PARAM_INT);
                $prepare->execute();
            } catch (PDOException $e) {
                // TODO: エラーをログに出力できるようにしたい
                echo 'Error: ' . $e->getMessage();
                die();
            }
        }
    }

    /**
     * 日にちの文字列型で勤怠情報を取得するメソッド
     * 日付の書き方は"0000-00-00 00:00:00"
     * 右側にワイルドカードを付けてるので、日時検索しやすいです
     *
     * @param string $date
     * @return array | NULL
     */
    public function getAttendanceFrom($date)
    {
        if (!empty($date)) {
            $mydbh = $this->dbh;
            $sql = "SELECT * FROM `attendances` WHERE start_time LIKE ?";
            $date = $date . "%";

            try {
                $prepare = $mydbh->prepare($sql);
                $prepare->bindValue(1, (string)$date, PDO::PARAM_STR);
                $prepare->execute();
                $result = $prepare->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // TODO: エラーをログに出力できるようにしたい
                echo 'Error: ' . $e->getMessage();
                die();
            }

            return $result ? $result : NULL;
        }
    }

    /**
     * 勤怠のIDで勤怠情報を取得するメソッド
     *
     * @param int $id
     * @return array | NULL
     */
    public function getAttendanceBy($id)
    {
        if (!empty($id)) {
            $mydbh = $this->dbh;
            $sql = "SELECT * FROM `attendances` WHERE id = ?";

            try {
                $prepare = $mydbh->prepare($sql);
                $prepare->bindValue(1, (int)$id, PDO::PARAM_INT);
                $prepare->execute();
                $result = $prepare->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // TODO: エラーをログに出力できるようにしたい
                echo 'Error: ' . $e->getMessage();
                die();
            }

            return $result ? $result : NULL;
        }
    }

    /**
     * 最後に出勤した勤怠情報をのIDを取得するメソッド
     *
     * @return int | NULL
     */
    public function getLatestAttendanceIdBy($userId) 
    {
        if (!empty($userId)) {
            $mydbh = $this->dbh;
            $sql = "SELECT MAX(id) FROM `attendances` GROUP BY user_id HAVING user_id = ?";

            try {
                $prepare = $mydbh->prepare($sql);
                $prepare->bindValue(1, (int)$userId, PDO::PARAM_INT);
                $prepare->execute();
                $result = $prepare->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // TODO: エラーをログに出力できるようにしたい
                echo 'Error: ' . $e->getMessage();
                die();
            }

            return $result ? (int)$result['MAX(id)'] : NULL;
        }
    }

    /**
     * Undocumented function
     *
     * @param int $id
     * @param string $day
     * @param string $hour
     * @param string $minute
     * @param string $comment
     * @param int $remarkId
     * @param int $internalBusinessId
     * @param int $userId
     * @return void
     */
    public function createRetirement($id, $hour, $minute, $comment, $remarkId, $internalBusinessId)
    {
        if (!empty($hour) && !empty($minute)) {

            $mydbh = $this->dbh;
            $latestAttendance = $this->getAttendanceBy($id);
            $day = mb_substr((string)$latestAttendance['start_time'], 0 ,11);
            $date = $day . $hour . ":" . $minute . ":00";
            $diffTIme = getDiffTime($latestAttendance['start_time'], $date);
            $sql = "UPDATE `attendances` SET end_time = ?, breaktime_minute = ?, comment = ?, remarks_id = ?, internal_business_id = ? WHERE id = ?";

            try {
                $prepare = $mydbh->prepare($sql);
                $prepare->bindValue(1, (string)$date, PDO::PARAM_STR);
                is_null($diffTIme['breakMinutes']) ? $prepare->bindValue(2, NULL, PDO::PARAM_NULL) : $prepare->bindValue(2, (int)$diffTIme['breakMinutes'], PDO::PARAM_INT);
                empty($comment) ? $prepare->bindValue(3, NULL, PDO::PARAM_NULL) : $prepare->bindValue(3, (string)$comment, PDO::PARAM_STR);
                empty($remarkId) ? $prepare->bindValue(4, NULL, PDO::PARAM_NULL) : $prepare->bindValue(4, (int)$remarkId, PDO::PARAM_INT);
                empty($internalBusinessId) ? $prepare->bindValue(5, NULL, PDO::PARAM_NULL) : $prepare->bindValue(5, (int)$internalBusinessId, PDO::PARAM_INT);
                $prepare->bindValue(6, (int)$id, PDO::PARAM_INT);
                $prepare->execute();
            } catch (PDOException $e) {
                // TODO: エラーをログに出力できるようにしたい
                echo 'Error: ' . $e->getMessage();
                die();
            }
        }
    }

    /**
     * 全種類の業務内容を取得するメソッド
     *
     * @return array 二次元配列
     */
    public function getBusinessTypes() {
        $mydbh = $this->dbh;
        $sql = "SELECT * FROM `business_types`";
        $prepare = $mydbh->query($sql);
        $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * 業務内容の種類を取得するメソッド
     *
     * @param int $id
     * @return array | NULL
     */
    public function getBusinessTypeBy($id)
    {
        if (!empty($id)) {
            $mydbh = $this->dbh;
            $sql = "SELECT * FROM `business_types` WHERE id = ?";

            try {
                $prepare = $mydbh->prepare($sql);
                $prepare->bindValue(1, (int)$id, PDO::PARAM_INT);
                $prepare->execute();
                $result = $prepare->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // TODO: エラーをログに出力できるようにしたい
                echo 'Error: ' . $e->getMessage();
                die();
            }

            return $result ? $result : NULL;
        }
    }


    /**
     * 全種類の管社内業務内容を取得するメソッド
     *
     * @return array 二次元配列
     */
    public function getInternalBusinessTypes() {
        $mydbh = $this->dbh;
        $sql = "SELECT * FROM `internal_business_types`";
        $prepare = $mydbh->query($sql);
        $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * 社内業務内容を種類を取得するメソッド
     *
     * @param int $id
     * @return array | NULL
     */
    public function getInternalBusinessTypeBy($id)
    {
        if (!empty($id)) {
            $mydbh = $this->dbh;
            $sql = "SELECT * FROM `internal_business_types` WHERE id = ?";

            try {
                $prepare = $mydbh->prepare($sql);
                $prepare->bindValue(1, (int)$id, PDO::PARAM_INT);
                $prepare->execute();
                $result = $prepare->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // TODO: エラーをログに出力できるようにしたい
                echo 'Error: ' . $e->getMessage();
                die();
            }

            return $result ? $result : NULL;
        }
    }


    /**
     * 全種類の備考を取得するメソッド
     *
     * @return array 二次元配列
     */
    public function getRemarks() {
        $mydbh = $this->dbh;
        $sql = "SELECT * FROM `remarks`";
        $prepare = $mydbh->query($sql);
        $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * 備考の種類を取得するメソッド
     *
     * @param int $id
     * @return array | NULL
     */
    public function getRemarkBy($id)
    {
        if (!empty($id)) {
            $mydbh = $this->dbh;
            $sql = "SELECT * FROM `remarks` WHERE id = ?";

            try {
                $prepare = $mydbh->prepare($sql);
                $prepare->bindValue(1, (int)$id, PDO::PARAM_INT);
                $prepare->execute();
                $result = $prepare->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // TODO: エラーをログに出力できるようにしたい
                echo 'Error: ' . $e->getMessage();
                die();
            }

            return $result ? $result : NULL;
        }
    }
}

?>
