</main>

<!-- 
    PDFを表示する処理
        * get_html_data(ダミーデータを取得するための関数)内でPDFに書き込みたい内容を記述する。
        * $pdf->setSourceFile()の引数は外部ファイルの `get_pdf_template.php` で作成したPDFテンプレート。
 -->

<footer>
<br>
<a href="./includes/for_dummy_data/pages/pdf_download.php" class="btn footer-btn">PDFダウンロード</a>
<?php
    require_once(dirname(__FILE__) . "/../../lib/TCPDF/tcpdf.php");
    require_once(dirname(__FILE__) . "/../../lib/TCPDF/FPDI/autoload.php");
    require_once(dirname(__FILE__) . "/../for_dummy_data/func/get_html_data.php");
    require_once(dirname(__FILE__) . "/../for_dummy_data/pages/pdf_template.php");
    // Position NUM
    define(RIGHT, 10);
    define(TOP, 15);
    define(LEFT, 10);

    use setasign\Fpdi\TcpdfFpdi;
    $pdf = new TcpdfFpdi();
   
    // Margin制御
    $pdf->SetMargins(LEFT, TOP, RIGHT);
    // Set-Cell-padding
    $pdf->SetCellPadding(0);
    // Auto New Page
    $pdf->SetAutoPageBreak(false);
   
    // header:no use
    // footer:no use
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
   
    // Prepare 1 page
    $pdf->AddPage('P','A4');
   
    // Set Font-Type(For Japanese)
    $font = "kozgopromedium";
    $pdf->SetFont($font, '', 8);
   
    // PDF READING
    $pdf->setSourceFile(dirname(__FILE__) . '/../for_dummy_data/pdf/template.pdf');
    $page = $pdf->importPage(1);
    $pdf->useTemplate($page);
   
    // Output-\n
    $pdf->Ln();
    $pdf->Ln();

    // Get Dummy_data
    $html=get_html_data();

    $tcpdf->setAutoPageBreak(true,20);
    // Writting HTML >>> PDF
    $pdf->writeHTML($html, true, 0, true, 0);
    
    // Output-PDF
    $pdf->Output(dirname(__FILE__) . '/../for_dummy_data/pdf/kinmu-hyo.pdf', 'F');
?>
<br><br>
<!-- ここのdata属性は、取得したPDFのパスを指定 -->
<object data="./includes/for_dummy_data/pdf/kinmu-hyo.pdf" type="application/pdf" width="100%" height="600px"></object>
</footer>
</body>
</html>
