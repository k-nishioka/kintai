<?php

require_once(dirname(__FILE__) . "/includes/network/database.php");
require_once(dirname(__FILE__) . "/includes/function.php");


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


require_once(dirname(__FILE__) . "/includes/template-parts/header.php");
?>

<section id="attendance-main">
    <div class="inner">
        <div class="attendance-main-wrapper">

            <div class="main-header content-between">
                <div class="left">
                    <h4 class="subtitle-font">松崎の勤務時間表</h4>
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

            <div class="main-admin-btns">
                <div class="me-btn-wrapper">
                    <a href="#" class="form-button">自分の勤務表を見る</a>
                </div>
                <form method="POST">
                    <div class="reset-select-style form-select">
                        <select name="boss" required>
                            <option value="" hidden>ユーザーを選んでください</option>
                            <option value="1" hidden>松崎</option>
                        </select>
                    </div>
                    <div class="for-center">
                        <input class="form-button subtitle-font" type="submit" value="選択" name="registration">
                    </div>
                </form>
            </div>

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
                        <th>深夜残業時間</th>
                        <th>合計</th>
                        <th>社外業務</th>
                        <th>社内業務</th>
                        <th>社内業務内容</th>
                        <th>備考</th>
                    </tr>
                    <?php
                        for ($day = 1; $day <= $currentMonthDays; $day++):
                            $dateTime = strtotime($currentMonth . '-' . $day);
                            $dayOfWeek = date('w', $dateTime);
                            if ($dayOfWeek == 0) {
                                echo '<tr class="sunday-style">';
                            } elseif ($dayOfWeek == 6) {
                                echo '<tr class="saturday-style">';
                            } else {
                                echo '<tr>';
                            }
                    ?>
                            <td><?php echo $day; ?>日</td>
                            <td><?php echo $week[$dayOfWeek]; ?>曜</td>
                            <td>10:00</td>
                            <td>20:00</td>
                            <td>60分</td>
                            <td>8時間</td>
                            <td>1時間</td>
                            <td></td>
                            <td>9時間</td>
                            <td>9時間</td>
                            <td></td>
                            <td></td>
                            <td>遅刻遅遅刻遅遅刻遅遅刻遅遅刻遅遅刻遅遅刻遅遅刻遅遅刻遅刻</td>
                        </tr>
                    <?php endfor; ?>
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
                            <th>深夜残業合計</th>
                            <th>合計</th>
                        </tr>
                        <tr>
                            <td>0日</td>
                            <td>22日</td>
                            <td>176時間</td>
                            <td>4時間</td>
                            <td>0時間</td>
                            <td>180時間</td>
                        </tr>
                    </table>
                    <table class="comment-table">
                        <tr>
                            <th>備考</th>
                            <td>9/2 電車遅延で遅刻<br>9/3 電車遅延で遅刻</td>
                        </tr>
                    </table>
                </div>
                <div class="main-footer-btns">
                    <div class="for-center ">
                        <a href="#" class="form-button">出社する</a>
                        <a href="#" class="form-button">PDFダウンロード</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php require_once(dirname(__FILE__) . "/includes/template-parts/footer.php"); ?>
