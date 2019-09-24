<!-- 
    PDFをダウンロードするページ
        * get_html_data(ダミーデータを取得するための関数)内で書き込みたい内容を記述する。
        * $pdf->setSourceFile()の引数は外部ファイルの `get_pdf_template.php` で作成したPDFテンプレート。
 -->

<?php
   
    require_once(dirname(__FILE__) . "/lib/TCPDF/tcpdf.php");
    require_once(dirname(__FILE__) . "/lib/TCPDF/FPDI/autoload.php");
    require_once(dirname(__FILE__) . "/get_html_data.php");

    use setasign\Fpdi\TcpdfFpdi;
    $pdf = new TcpdfFpdi();
   
    // Set-Margin
    $pdf->SetMargins(0, 0, 0);
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
    $pdf->setSourceFile('./template.pdf');
    $page = $pdf->importPage(1);
    $pdf->useTemplate($page);
   
    // Output-Text
    $pdf->Text(10, 10, 'ABCDE');
   
    // Output-/n
    $pdf->Ln();
   
    // Output-Cell
    //$pdf->Cell(70, 10, '', 1, 0, 'L');
   
    $pdf->Ln();

    // Get Dummy_data
    $html=get_html_data();

    // Writting HTML >>> PDF
    $pdf->writeHTML($html, true, 0, true, 0);
   
    // Output-img
    //$pdf->Image('./image/image.jpg', 10, 10);
    
    ob_end_clean();
    $date = date( 'Ym',time());
    // Download-PDF
    $pdf->Output(sprintf('%s_kinmu-hyo.pdf',$date), 'D');
