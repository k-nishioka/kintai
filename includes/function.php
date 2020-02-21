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
 *  'intervalSeconds', 'intervalMinutes', 'intervalHours', 'intervalDays',
 *  'H', 'm', 's', 'H:m', 'H:m:s', 'm:s',
 *  'workTime', 'overTime', 'midnight', 'specialTimes'
 * }
 *
 * @param string $startTime
 * @param string $endTime
 * @param int $breakTimeMinutes
 * @return array 
 */
function getDiffTime($startTime, $endTime, $breakTimeMinutes)
{

    $diffTime = array();

    if (is_null($endTime)) return NULL;

    $start = strtotime($startTime);
    $end = strtotime($endTime);

    $intervalSeconds = ($start >= $end) ? ($start - $end) : ($end - $start);
    $intervalMinutes = floor($intervalSeconds / 60);
    $intervalHours = floor($intervalMinutes / 60);
    $intervalDays = floor($intervalHours / 24);
    $diffSeconds = $intervalSeconds % 60;
    $diffMinutes = $intervalMinutes % 60;
    $diffHours = $intervalHours % 60;

    $totalWorkTime = $intervalMinutes / 60;
    $breakTimeHours = $breakTimeMinutes / 60;
    $workTime = $totalWorkTime - $breakTimeHours;

    $overTime = $workTime > 8 ? $workTime - 8 : NULL;

    $workDay = date('Y-m-d', mktime(0, 0, 0, date('m', $start), date('d', $start), date('Y', $start)));
    $midnight = strtotime($workDay . " 22:00:00");
    $intervalMidSeconds = $end > $midnight ? $end - $midnight : 0;
    $intervalMidMinutes = floor($intervalMidSeconds / 60);
    $midnightHours = $intervalMidMinutes / 60;

    $specialTimeArray = array($workTime, $overTime, $midnightHours);
    $specialTimes = array();

    foreach($specialTimeArray as $i => $value) :
        $partHour = floor($value);
        $partMinute = ($value - $partHour) * 60;
        $specialTimes[$i] = sprintf("%02d",$partHour) . ":" . sprintf("%02d",$partMinute);
    endforeach;

    $diffTime = array(
        'intervalSeconds' => $intervalSeconds,
        'intervalMinutes' => $intervalMinutes,
        'intervalHours' => $intervalHours,
        'intervalDays' => $intervalDays,
        'H' => $diffHours,
        'm' => $diffMinutes,
        's' => $diffSeconds,
        'H:m' => sprintf('%02d', $diffHours) . ':' . sprintf('%02d', $diffMinutes),
        'H:m:s' => sprintf('%02d', $diffHours) . ':' . sprintf('%02d', $diffMinutes) . ':' . sprintf('%02d', $diffSeconds),
        'm:s' => sprintf('%02d', $diffMinutes) . ':' . sprintf('%02d', $diffSeconds),
        'workTime' => $workTime,
        'overTime' => $overTime,
        'midnight' => $midnightHours,
        'specialTimes' => $specialTimes,
    );

    return $diffTime;
}

?>
