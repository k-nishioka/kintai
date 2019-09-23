<!-- メインページ -->

<?php

require_once(dirname(__FILE__) . "/includes/network/database.php");
require_once(dirname(__FILE__) . "/includes/template-parts/header.php");
$db = new Database();

?>

<!-- ここで指定するdata属性は、表示させたいファイル -->
<br><br><object data="./test.pdf" type="application/pdf" width="100%" height="600px"></object>

<?php 
require_once(dirname(__FILE__) . "/includes/template-parts/footer.php");
?>

