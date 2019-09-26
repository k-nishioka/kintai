<?php

require_once(dirname(__FILE__) . "/network/database.php");


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

        $dbh = new Database();
        $user = $dbh->getUserFrom($mail);

        if (password_verify($pass, $user['pass'])) {

            echo 'ログイン成功';
            // session_start();
            // $_SESSION['my_id'] = $user['id'];
            // $_SESSION['my_name'] = $user['name'];

            // TODO:   本番環境ではパスを変更する/
            header("Location: /attendance_management/index.php");
        }
    }
}

/**
 * ユーザーがログイン状態かどうかを確認するメソッド
 *
 * @return void
 */
function checkUserLoggedIn() {

    session_start();
    if (empty($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

/**
 * 2つの日付の差を返すメソッド
 * 返り値は連想配列でキーは,下の種類
 * {
 *  'intervalSeconds', 'intervalMinuts', 'intervalHours', 'intervalDays',
 *  'H', 'm', 's', 'H:m', 'H:m:s', 'm:s',
 *  'workTime', 'overTime', 'midnight',
 * }
 *
 * @param string $startTime
 * @param string $endTime
 * @return array 
 */
function getDiffTime($startTime, $endTime, $breakTime=NULL)
{

    $diffTime = array();

    if (is_null($endTime)) return NULL;

    $start = strtotime($startTime);
    $end = strtotime($endTime);

    $intervalSeconds = $end - $start;
    $intervalMinutes = floor($intervalSeconds / 60);
    $intervalHours = floor($intervalMinutes / 60);
    $intervalDays = floor($intervalHours / 24);
    $diffSeconds = $intervalSeconds % 60;
    $diffMinuts = $intervalMinutes % 60;
    $diffHours = $intervalHours % 60;

    $breakMinutes = !is_null($breakTime) ? $breakTime : 0;
    $workTime = ($intervalMinutes - $breakMinutes) / 60;
    $overTime = $workTime > 8 ? $workTime - 8 : 0;

    $workDay = date('Y-m-d', mktime(0, 0, 0, date('m', $start), date('d', $start), date('Y', $start)));
    $midnight = strtotime($workDay . " 22:00:00");
    $intervalMidSeconds = $end > $midnight ? $end - $midnight : 0;
    $intervalMidMinutes = floor($intervalMidSeconds / 60);
    $midnightWork = $intervalMidMinutes / 60;

    $diffTime = array(
        'intervalSeconds' => $intervalSeconds,
        'intervalMinutes' => $intervalMinutes,
        'intervalHours' => $intervalHours,
        'intervalDays' => $intervalDays,
        'H' => $diffHours,
        'm' => $diffMinuts,
        's' => $diffSeconds,
        'H:m' => sprintf('%02d', $diffHours) . ':' . sprintf('%02d', $diffMinuts),
        'H:m:s' => sprintf('%02d', $diffHours) . ':' . sprintf('%02d', $diffMinuts) . ':' . sprintf('%02d', $diffSeconds),
        'm:s' => sprintf('%02d', $diffMinuts) . ':' . sprintf('%02d', $diffSeconds),
        'workTime' => $workTime,
        'overTime' => $overTime,
        'midnight' => $midnightWork,
    );

    return $diffTime;
}








?>
