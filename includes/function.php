<?php


/**
 * ユーザーがログイン状態かどうかを確認するメソッド
 *
 * @return void
 */
function checkUserLoggedIn() {

    session_start();
    if (empty($_SESSION['user_id'])) {
        header("Location: /attendance_management/pages/login.php");
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
function getDiffTime($startTime, $endTime)
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

    $totalWorkTime = $intervalMinutes / 60;
    $breakHours = $totalWorkTime > 8 ? 1 : 0;
    $inMinFraction = $intervalMinutes / 60;
    $workTime = !is_null($breakHours) ? $inMinFraction - $breakHours : $totalWorkTime;
    $overTime = $workTime > 8 ? $workTime - 8 : 0;

    $workDay = date('Y-m-d', mktime(0, 0, 0, date('m', $start), date('d', $start), date('Y', $start)));
    $midnight = strtotime($workDay . " 22:00:00");
    $intervalMidSeconds = $end > $midnight ? $end - $midnight : 0;
    $intervalMidMinutes = floor($intervalMidSeconds / 60);
    $midnightHours = $intervalMidMinutes / 60;

    $breakHours = sprintf("%02d",$breakHours) . ":00";
    $timeArr = array($workTime,$overTime,$midnightHours);
    $specialStrTimes = array();

    foreach($timeArr as $i => $value) :
        $partHour = floor($value);
        $partMinute = ($value - $partHour) * 60;
        $specialStrTimes[$i] = sprintf("%02d",$partHour) . ":" . sprintf("%02d",$partMinute);
    endforeach;

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
        'midnight' => $midnightHours,
        'specialStrTimes' => $specialStrTimes,
        'breakHours' => $breakHours,
    );

    return $diffTime;
}

?>
