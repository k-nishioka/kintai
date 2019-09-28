<?php

require_once(dirname(__FILE__) . "/database.php");


/**
 * ユーザーをログインさせるためのメソッド
 * - ログインできれば、そのままindex.phpにジャンプする
 * - セッションへの接続もそのままする
 *
 * @param string $mail
 * @param string $pass
 * @return void
 */
function userLogin($mail, $pass)
{
    if (!empty($mail) && !empty($pass)) {

        $mail = htmlspecialchars($mail, ENT_QUOTES, 'utf-8');
        $pass = htmlspecialchars($pass, ENT_QUOTES, 'utf-8');

        $db = new Database();
        $user = $db->getUserFrom($mail);

        if (password_verify($pass, $user['pass'])) {

            session_start();
            $_SESSION['user_id'] = $user['id'];
            // TODO:   本番環境ではパスを変更する/
            header("Location: /attendance_management/index.php");
        }
    }
}

/**
 * ユーザーが種菌済みかを書くんするメソッド
 *
 * @return bool
 */
function checkAttendance()
{

    $db = new Database();
    $latestAttendanceId = $db->getLatestAttendanceIdBy($_SESSION['user_id']);
    if (is_null($latestAttendanceId)) return false;
    $latestAttendance = $db->getAttendanceBy($latestAttendanceId);
    if (is_null($latestAttendance)) return false;
    return is_null($latestAttendance['end_time']) ? true : false;
}

?>
