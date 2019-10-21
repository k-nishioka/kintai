<?php  

require_once(dirname(__FILE__).'/../../network/database.php');
require_once(dirname(__FILE__) . "/../../network/function.php");
require_once(dirname(__FILE__) . "/../../function.php");

    /**
     * 勤務情報(ヒアドキュメント形式)取得のためのメソッド
     * @param  int $me 現在のユーザid
     * @param  int $currentMonthDays 月末日
     * @param  string $currentMonth 'Y-m'
     * @return string $html
     */
    function getHereDocument($user_id,$currentMonthDays,$currentMonth){

        // 初期化
        $style='';
        $html='';
        $html_thread='';
        $html_data='';
        $totalPaidDays = 0;
        $totalWorkDays = 0;
        $totalWorkTimes = 0;
        $totalOverTimes = 0;
        $totalMidnightTimes = 0;
        $comments = '';

        $db = new Database();
        // $me:ユーザ情報
        $me = $db->getUserBy($user_id);
        $users = $db->getUsers();
        $isAttendance = checkAttendance();

        $today = date('Y-m-j');
        $currentTime = strtotime($currentMonth . '-01');
        $dateTitle = date('Y年m月', $currentTime);
        $week = array("日", "月", "火", "水", "木", "金", "土");

        // 表示しているページの社員情報を出力する
        $html_thread='<div><h2>'.$dateTitle.'度勤務表</h2></div>'.
        '<div class="main-header">社員番号:'.$me['employee_num'].'<br><br>'.
        '名前:'.$me['name'].'</div>';
        // 勤務表のカラムをドキュメントに追加する
        $html_thread = $html_thread.
        '<section><div class="main-content">
        <table>
            <tr><th>日にち</th>
                <th>曜日</th>
                <th>出社時間</th>
                <th>退社時間</th>
                <th>休憩時間</th>
                <th>勤務時間</th>
                <th>残業時間</th>
                <th>深夜時間</th>
                <th>社外業務</th>
                <th>社内業務</th>
                <th class="ib_content">社内業務内容</th>
                <th>備考</th></tr>';

        for ($day = 1; $day <= $currentMonthDays; $day++):

            $dateTime = strtotime($currentMonth . '-' . $day);
            $dayOfWeek = date('w', $dateTime);
            $attendance = $db->getAttendanceFrom($me['id'] ,date('Y-m-d', $dateTime));
            if ($dayOfWeek == 0) {
                $html_data = $html_data.'<tr class="sunday-style">'; 
            } elseif ($dayOfWeek == 6) {
                $html_data = $html_data . '<tr class="saturday-style">'; 
            } else {
                $html_data = $html_data . '<tr>';
            }

            // 勤務情報がない場合、日にちと曜日だけを出力
            // 勤務情報がある場合、日にちと曜日＋勤務情報を出力
                if (is_null($attendance)):
                    $html_data = $html_data.'<td>'.$day.'日</td><td>'.$week[$dayOfWeek].'曜</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
                else:
                    $startTime = substr($attendance['start_time'], 11, 5);
                    $endTime = substr($attendance['end_time'], 11, 5);  
                    $diffTime = getDiffTime($attendance['start_time'], $attendance['end_time'], $attendance['breaktime_minute']);
                    $internalType = $db->getInternalBusinessTypeBy($attendance['internal_business_id']);
                    $remark = $db->getRemarkBy($attendance['remarks_id']);

                    $workTime = $diffTime['workTime'];
                    $overTime = $diffTime['overTime'];
                    $midnight = $diffTime['midnight'];

                    $html_data = $html_data. '
                    <td>'.$day.'日</td>
                    <td>'.$week[$dayOfWeek].'曜</td>
                    <td>'.$startTime.'</td>
                    <td>'.$endTime.'</td>
                    <td>'.$attendance['breaktime_minute'].'分</td>
                    <td>'.$workTime.'時間</td>
                    <td>'.$overTime.'時間</td>
                    <td>'.$midnight.'時間</td>';

                    // 勤務種別の分岐
                    if ($attendance['business_type_id'] == 1) {
                        $html_data = $html_data. '<td></td><td>'.$diffTime['workTime'].'時間</td>';
                    } elseif ($attendance['business_type_id'] == 2) {
                        $html_data = $html_data. '<td>'.$diffTime['workTime'].'時間</td><td></td>';
                    } else {
                        $html_data = $html_data. '<td></td><td></td>';
                    }
                    $html_data = $html_data. 
                        '<td>'.$internalType['name'].'</td>
                        <td>'.$remark['name'].'</td></tr>';

                    if ($remark['id'] == 1){
                        $totalPaidDays++;
                    }
                    $totalWorkDays++;
                    $totalWorkTimes += $workTime;
                    $totalOverTimes += $overTime;
                    $totalMidnightTimes += $midnight;

                    // 備考があれば、備考を出力
                    if (!is_null($attendance['comment'])) $comments = $attendance['comment'];
               endif;
        endfor;
        $html_data = $html_data.'</table></div>';
 
        if ($comments == '') $comments = '備考はありません';
        // $html = $html.'<br>'.
        $html_etc = $html_etc.'<br>'.
        '<div class="main-footer content-between mobile-content-wrap">
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
                        <td>'.$totalPaidDays.'日</td>
                        <td>'.$totalWorkDays.'日</td>
                        <td>'.$totalWorkTimes.'時間</td>
                        <td>'.$totalOverTimes.'時間</td>
                        <td>'.$totalMidnightTimes.'時間</td>
                    </tr>
                </table>
                <table class="comment-table">
                    <tr>
                        <th>備考</th>
                        <td>'.$comments.'</td>
                    </tr>
                </table>
            </div>
        </div></section>';

        $html = $html_thread.$html_data.$html_etc;
        return $html;
    }
?>