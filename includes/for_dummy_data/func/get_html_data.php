<?php  
function get_html_data(){
    $html= <<< EOF
    <h1>PDFダミーデータ</h1>
    <h1>2019年9月勤務表</h1>
    <h2>社員番号：XXXXXX    名前：山田太郎</h2>
    <table border="1">
        <tr>
            <th>日にち</th>
            <th>曜日</th>
            <th>出社時間</th>
            <th>退社時間</th>
            <th>休憩時間</th>
            <th>勤務時間</th>
            <th>普通残業</th>
            <th>深夜残業</th>
        </tr>
        <tr>
            <td>1</td>
            <td>月曜日</td>
            <td>9:00</td>
            <td>18:00</td>
            <td>1:00</td>
            <td>8:00</td>
            <td>0:00</td>
            <td>0:00</td>
        </tr>
        <tr>
            <td>2</td>
            <td>火曜日</td>
            <td>9:00</td>
            <td>18:00</td>
            <td>1:00</td>
            <td>8:00</td>
            <td>0:00</td>
            <td>0:00</td>
        </tr>
    </table>
    EOF;

    return $html;
}

?>