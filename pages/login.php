<?php

require_once(dirname(__FILE__) . "/../includes/network/database.php");
require_once(dirname(__FILE__) . "/../includes/network/function.php");


$db = new Database();

if (isset($_POST['login'])) {
    userLogin($_POST['mail'], $_POST['pass']);
}


require_once(dirname(__FILE__) . "/../includes/template-parts/header.php");

?>

<section id="admin-registration">
    <div class="inner">
        <div class="form-wrapper">
            <h2 class="title-font">ログイン画面</h2>
            <form method="POST">
                <p class="subtitle-font">メールアドレス</p>
                <input class="form-text" type="email" name="mail" required>
                <p class="subtitle-font">パスワード</p>
                <input class="form-text" type="password" name="pass" required>
                <div class="for-center">
                    <input class="form-button subtitle-font" type="submit" value="ログイン" name="login">
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once(dirname(__FILE__) . "/../includes/template-parts/footer.php"); ?>
