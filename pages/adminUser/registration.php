<?php

require_once(dirname(__FILE__) . "/../../includes/network/database.php");
require_once(dirname(__FILE__) . "/../../includes/function.php");


$db = new Database();

if (isset($_POST['registration'])) {
    $db->createUser($_POST['name'], $_POST['employeeNum'], $_POST['pass'], $_POST['mail'], true);
    userLogin($_POST['mail'], $_POST['pass']);
}

require_once(dirname(__FILE__) . "/../../includes/template-parts/header.php");

?>

<section id="admin-registration">
    <div class="inner">
        <div class="form-wrapper">
            <h2 class="title-font">管理者ユーザーの登録画面</h2>
            <form method="POST">
                <p class="subtitle-font">名前</p>
                <input class="form-text" type="text" name="name" required>
                <p class="subtitle-font">社員番号</p>
                <input class="form-text" type="text" pattern="\d*" oncopy="return false" onInput=”check_numtype(this)” name="employeeNum" required>
                <p class="subtitle-font">メールアドレス</p>
                <input class="form-text" type="email" name="mail" required>
                <p class="subtitle-font">パスワード</p>
                <input class="form-text" type="password" name="pass" required>
                <div class="for-center">
                    <input class="form-button subtitle-font" type="submit" value="登録" name="registration">
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once(dirname(__FILE__) . "/../../includes/template-parts/footer.php"); ?>
