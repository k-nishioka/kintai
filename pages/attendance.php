<?php

require_once(dirname(__FILE__) . "/../includes/network/database.php");
require_once(dirname(__FILE__) . "/../includes/function.php");

checkUserLoggedIn();

$db = new Database();
$businessTypes = $db->getBusinessTypes();


if (!empty($_POST['attendance'])) {
    $db->createAttendance($_POST['day'], $_POST['attend_hours'], $_POST['attend_minutes'], $_POST['business_type'], $_SESSION['user_id']);
    header("Location: /attendance_management/index.php");
}


require_once(dirname(__FILE__) . "/../includes/template-parts/header.php");

?>

<section id="attendance-post">
    <div class="inner">
        <div class="form-wrapper">
            <h2 class="title-font">ユーザーの出社画面</h2>
            <form method="POST">
                <p class="subtitle-font">日付</p>
                <input class="form-date" type="date" name="day" required>
                <p class="subtitle-font">時間</p>
                <div class="content-between">
                    <div class="reset-select-style form-select form-select-half" required>
                        <select name="attend_hours" required>
                            <option value="" hidden>時</option>
                            <?php for ($i = 1; $i <= 24; $i++): ?>
                                <option value="<?php echo sprintf('%02d', $i); ?>"><?php echo sprintf('%02d', $i); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <p class="attendance-time-label">：</p>
                    <div class="reset-select-style form-select form-select-half" required>
                        <select name="attend_minutes" required>
                            <option value="" hidden>分</option>
                            <?php for ($i = 0; $i < 60; $i = $i + 15): ?>
                                <option value="<?php echo sprintf('%02d', $i) ?>"><?php echo sprintf('%02d', $i); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <p class="subtitle-font">業務種別</p>
                <div class="reset-select-style form-select" required>
                    <select name="business_type" required>
                        <option value="" hidden>業種を選んでください</option>
                        <?php foreach ($businessTypes as $businessType): ?>
                            <option value="<?php echo $businessType['id']; ?>"><?php echo $businessType['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="for-center">
                    <input class="form-button subtitle-font" type="submit" value="出社" name="attendance">
                </div>
            </form>
        </div>
    </div>
</section>
