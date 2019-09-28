<?php

require_once(dirname(__FILE__) . "/../function.php");

$http = empty($_SESSION['HTTPS']) ? 'http://' : 'https://';
$domain = $http . $_SERVER['HTTP_HOST'];
$currentURL = $domain . $_SERVER['REQUEST_URI'];
/* ローカル環境用 */
$loginURL = "http://localhost/attendance_management/pages/login.php";
$adminURL = "http://localhost/attendance_management/pages/adminUser/registration.php";
/* 本番環境用 */
// $loginURL = $http . "cmsidiv.php.xdomain.jp/attendance_management/pages/login.php";
// $adminURL = $http . "cmsidiv.php.xdomain.jp/attendance_management/pages/adminUser/registration.php";

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>勤怠管理システム</title>
    <?php   //  TODO: 本番サーバーへの移行時は下の2つのCSSのパスを修正する ?>
    <link rel="stylesheet" href="/attendance_management/reset.css">
    <link rel="stylesheet" href="/attendance_management/style.css">
</head>

<body>
<header id="header">
    <div class="inner">
        <nav class="content-between">
            <!-- TODO: 本番環境では、index.phpにジャンプするようにする -->
            <h2 class="title-font"><a href="/attendance_management/index.php">勤怠管理システム</a></h2>
            <ul class="header-list">
            <?php if ($currentURL != $adminURL): ?>
                <?php if (empty($_SESSION['user_id'])): ?>
                    <?php if ($currentURL == $loginURL): ?>
                        <li class="header-item"><a href="/attendance_management/pages/registration.php" class="btn header-btn">ユーザー登録</a></li>
                    <?php else: ?>
                        <li class="header-item"><a href="/attendance_management/pages/login.php" class="btn header-btn">ログイン</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="header-item"><a href="/attendance_management/pages/logout.php<?php // echo $domain . "/attendance_management/pages/logout.php" ?>" class="btn header-btn btn-warning">ログアウト</a></li>
                <?php endif; ?>
            <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
<main>
