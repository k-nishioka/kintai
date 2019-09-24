<<<<<<< HEAD
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

    use setasign\Fpdi\TcpdfFpdi;
    $pdf = new TcpdfFpdi();
   
    // Set-Margin
    $pdf->SetMargins(25, 25, 25);
    // Set-Cell-padding
    $pdf->SetCellPadding(0);
    // Auto New Page
    $pdf->SetAutoPageBreak(false);
   
    // header:no use
    // footer:no use
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
   
    // Prepare 1 page
    $pdf->AddPage('L','A4');
   
    // Set Font-Type(For Japanese)
    $font = "kozgopromedium";
    $pdf->SetFont($font, '', 10);
   
    // PDF READING
    $pdf->setSourceFile(dirname(__FILE__) . '/../for_dummy_data/pdf/template.pdf');
    $page = $pdf->importPage(1);
    $pdf->useTemplate($page);
   
    // Output-\n
    $pdf->Ln();
    $pdf->Ln();

    // Get Dummy_data
    $html=get_html_data();

    // Writting HTML >>> PDF
    $pdf->writeHTML($html, true, 0, true, 0);
    
    // Output-PDF
    $pdf->Output(dirname(__FILE__) . '/../for_dummy_data/pdf/kinmu-hyo.pdf', 'F');
?>
<br><br>
<!-- ここのdata属性は、取得したPDFのパスを指定 -->
<object data="./includes/for_dummy_data/pdf/kinmu-hyo.pdf" type="application/pdf" width="100%" height="600px"></object>
=======


>>>>>>> 77e321c5ca8e504bae2370da51d2891eaa98d362
</footer>

</main>
</body>
</html>
