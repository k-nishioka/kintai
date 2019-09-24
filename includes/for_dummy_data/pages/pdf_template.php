<!-- テンプレート(白紙)のPDFを取得するページ -->

<?php

    require_once(dirname(__FILE__) . "/../../../lib/TCPDF/tcpdf.php");
    require_once(dirname(__FILE__) . "/../../../lib/TCPDF/FPDI/autoload.php");

    /* class import */
    // setasign\Fpdi >>> Fpdi.php-namespace
    use setasign\Fpdi\TcpdfFpdi;

    /* variable */
    // font(For Japanese)-
    $font="ipaexm";

    /* PDF set Init */
    // $tcpdf=new TcpdfFpdi('L','mm','A4');
    $tcpdf=new TCPDF("L","mm","A4",true,"UTF-8");

    // Set-Margin
    $tcpdf->SetMargins(25, 0, 25);
    // Auto New Page
    $tcpdf->setAutoPageBreak(false);
    // Header:invalid, Footer:invalid
    $tcpdf->setPrintHeader(false);
    $tcpdf->setPrintFooter(false);

    $tcpdf->Output(dirname(__FILE__) . '/../pdf/template.pdf','F');
?>

