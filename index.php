<?php

// ini_set( 'display_errors', 1 );
// ini_set( 'error_reporting', E_ALL );
require_once(dirname(__FILE__) . "/includes/network/database.php");
require_once(dirname(__FILE__) . "/includes/network/function.php");

// TODO: 下の4行はログインが繋ぎこみ完了したら削除する
if (empty($_SESSION['user_id'])) {
    session_start();
    $_SESSION['user_id'] = 1;
}

checkUserLoggedIn();

$db = new Database();

$me = $db->getUserBy(1);
$users = $db->getUsers();
$currentUser = !is_null($_SESSION['current_user']) ? $db->getUserBy($_SESSION['current_user']) : $me;
$isAttendance = checkAttendance();


date_default_timezone_set('Asia/Tokyo');

$dateTitle = "0000年00月";
$prevMonth = "0000-00";
$nextMonth = "0000-00";
$week = array("日", "月", "火", "水", "木", "金", "土");

$today = date('Y-m-j');
$currentMonth = isset($_GET['date']) ? $_GET['date'] : date('Y-m');
$currentTime = strtotime($currentMonth . '-01');
$currentMonthDays = date('t', $currentTime);

$dateTitle = date('Y年m月', $currentTime);
$prevMonth = date('Y-m', mktime(0, 0, 0, date('m', $currentTime) - 1, 1, date('Y', $currentTime)));
$nextMonth = date('Y-m', mktime(0, 0, 0, date('m', $currentTime) + 1, 1, date('Y', $currentTime)));


$totalPaidDays = 0;
$totalWorkDays = 0;
$totalWorkTimes = 0;
$totalOverTimes = 0;
$totalMidnightTimes = 0;
$comments = '';


if (!empty($_POST['choice_user'])) {
    echo '選択ボタン完了';
    if (!empty($_POST['attendance_user'])) {
        echo 'ユーザーが選択されている';
        $currentUserId = $db->getUserBy($_POST['attendance_user']);
        $_SESSION['current_user'] = $currentUserId['id'];
        header("Location: index.php?date=" . $currentMonth);
    }
} elseif (!empty($_POST['choice_self'])) {
    echo '自分ボタン完了';
    $_SESSION['current_user'] = $me['id'];
    header("Location: index.php?date=" . $currentMonth);
}


require_once(dirname(__FILE__) . "/includes/template-parts/header.php");

?>

<section id="attendance-main">
    <div class="inner">
        <div class="attendance-main-wrapper">

            <div class="main-header content-between">
                <div class="left">
                    <h4 class="subtitle-font"><?php echo $currentUser['name']; ?>の勤務時間表</h4>
                </div>
                <div class="right content-between">
                    <a href="?date=<?php echo $prevMonth; ?>">＜前の月</a>
                    <p class="date-title"><?php echo $dateTitle; ?></p>
                    <?php
                        if (strtotime($nextMonth . '-01') > strtotime(date('Y-m-j'))) {
                            echo '<p>次の月＞</p>';
                        } else {
                            echo '<a href="?date=' . $nextMonth . '">次の月＞</a>';
                        }
                    ?>
                </div>
            </div>

            <?php if ($me['is_admin'] == 1): ?>
                <div class="main-admin-btns">
                    <form method="POST">
                        <div class="for-center">
                            <input class="form-button subtitle-font" type="submit" value="自分の勤務表を見る" name="choice_self">
                        </div>
                        <div class="reset-select-style form-select">
                            <select name="attendance_user">
                                <option value="" hidden>ユーザーを選んでください</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?php echo $user['id']; ?>"><?php echo $user['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="for-center">
                            <input class="form-button subtitle-font" type="submit" value="選択" name="choice_user">
                        </div>
                    </form>
                </div>
            <?php endif;?>

            <div class="main-content">
                <table>
                    <tr>
                        <th>日にち</th>
                        <th>曜日</th>
                        <th>出社時間</th>
                        <th>退社時間</th>
                        <th>休憩時間</th>
                        <th>勤務時間</th>
                        <th>残業時間</th>
                        <th>深夜時間</th>
                        <th>社外業務</th>
                        <th>社内業務</th>
                        <th>社内業務内容</th>
                        <th>備考</th>
                    </tr>
                    <?php
                        for ($day = 1; $day <= $currentMonthDays; $day++):

                            $dateTime = strtotime($currentMonth . '-' . $day);
                            $dayOfWeek = date('w', $dateTime);
                            $attendance = $db->getAttendanceFrom(date('Y-m-d', $dateTime));

                            if ($dayOfWeek == 0) {
                                echo '<tr class="sunday-style">';
                            } elseif ($dayOfWeek == 6) {
                                echo '<tr class="saturday-style">';
                            } else {
                                echo '<tr>';
                            }

                            if (is_null($attendance)):
                                echo '<td>' . $day . '日</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
                            else:
                                $startTime = substr($attendance['start_time'], 11, 5);
                                $endTime = substr($attendance['end_time'], 11, 5);  
                                $diffTime = getDiffTime($attendance['start_time'], $attendance['end_time'], $attendance['breaktime_minute']);
                                $internalType = $db->getInternalBusinessTypeBy($attendance['internal_business_id']);
                                $remark = $db->getRemarkBy($attendance['remarks_id']);
                    ?>
                            <td><?php echo $day; ?>日</td>
                            <td><?php echo $week[$dayOfWeek]; ?>曜</td>
                            <td><?php echo $startTime ?></td>
                            <td><?php echo $endTime; ?></td>
                            <td><?php echo $attendance['breaktime_minute']; ?>分</td>
                            <td><?php echo $diffTime['workTime']; ?>時間</td>
                            <td><?php echo $diffTime['overTime']; ?>時間</td>
                            <td><?php echo $diffTime['midnight']; ?>時間</td>
                            <?php
                                if ($attendance['business_type_id'] == 1) {
                                    echo '<td></td><td>' . $diffTime['workTime'] . '時間</td>';
                                } elseif ($attendance['business_type_id'] == 2) {
                                    echo '<td>' . $diffTime['workTime'] .'時間</td><td></td>';
                                } else {
                                    echo '<td></td><td></td>';
                                }
                            ?>
                            <td><?php echo $internalType['name']; ?></td>
                            <td><?php echo $remark['name']; ?></td>
                        </tr>
                    <?php
                                if ($remark['id'] == 1) $totalPaidDays++;
                                $totalWorkDays++;
                                $totalWorkTimes += $diffTime['workTime'];
                                $totalOverTimes += $diffTime['overTime'];
                                $totalMidnightTimes += $diffTime['midnight'];
                                if (!is_null($attendance['comment'])) $comments = $attendance['comment'] . '<br>';
                            endif;
                        endfor; 
                    ?>
                </table>
            </div>

            <div class="main-footer content-between">
                <div class="main-footer-table">
                    <table>
                        <tr>
                            <th>有給日数</th>
                            <th>出勤日数</th>
                            <th>勤務時間合計</th>
                            <th>残業時間合計</th>
                            <th>深夜時間合計</th>
                        </tr>
                        <tr>
                            <td><?php echo $totalPaidDays; ?>日</td>
                            <td><?php echo $totalWorkDays; ?>日</td>
                            <td><?php echo $totalWorkTimes; ?>時間</td>
                            <td><?php echo $totalOverTimes; ?>時間</td>
                            <td><?php echo $totalMidnightTimes; ?>時間</td>
                        </tr>
                    </table>
                    <table class="comment-table">
                        <tr>
                            <th>備考</th>
                            <?php if ($comments == '') $comments = '備考はありません'; ?>
                            <td><?php echo $comments; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="main-footer-btns">
                    <div class="for-center ">
                        <?php if ($isAttendance): ?>
                            <a href="pages/retirement.php" class="form-button">退社する</a>
                        <?php else: ?>
                            <a href="pages/attendance.php" class="form-button">出社する</a>
                        <?php endif; ?>
                        <a href="#" class="form-button">PDFダウンロード</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php require_once(dirname(__FILE__) . "/includes/template-parts/footer.php"); ?>
