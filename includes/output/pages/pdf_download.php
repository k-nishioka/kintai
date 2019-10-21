<!-- 
    PDFをダウンロードする処理
        * getHereDocument(勤怠管理情報をPDFとして出力するための関数)でPDFを出力する。
 -->

<?php
    ob_start();
   
    require_once(dirname(__FILE__) . "/../func/getHereDocument.php");
    require_once(dirname(__FILE__) . "/../../function.php");
    require_once(dirname(__FILE__) . "/../../network/database.php");
    require_once(dirname(__FILE__) . "/../../../lib/vendor/autoload.php");

    checkUserLoggedIn();
    $currentId = $_SESSION['user_id'];
    $currentMonthDays=$_GET['cmd'];
    $currentMonth=$_GET['cm'];

    // PDF設定
    $mpdf = new \Mpdf\Mpdf([
        'fontdata' => [
            'ipa' => [
                'R' => 'ipam.ttf'
            ]
        ],
        'format' => 'A3-P'
    ]);

    $stylesheet = file_get_contents('style.css');
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
    $stylesheet = file_get_contents('reset.css');
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

   // ヒアドキュメント形式の勤務情報を取得する
    $html=getHereDocument($currentId,$currentMonthDays,$currentMonth);

    // PDFに取得した勤務情報を出力する
    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::DEFAULT_MODE);

    ob_end_clean();
   
    $currentTime = strtotime($currentMonth . '-01');
    $dateTitle = date('Y年m月', $currentTime);

    // Download-PDF
    $mpdf->Output(sprintf('%s度勤務表.pdf',$dateTitle), 'D');
    header("Location: /../../../index.php");

