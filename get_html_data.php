<?php  
function get_html_data(){
    $html= <<< EOF
    <h1>PDFダミーデータ</h1>
    <h1>テスト結果</h1>
    <table border="1">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Japanese</th>
            <th>Mathematic</th>
            <th>English</th>
        </tr>
        <tr>
            <td>1</td>
            <td>Tanaka</td>
            <td>50</td>
            <td>50</td>
            <td>50</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Suzuki</td>
            <td>40</td>
            <td>60</td>
            <td>50</td>
        </tr>
    </table>
    EOF;

    return $html;
}

?>