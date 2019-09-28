<?php

require_once(dirname(__FILE__) . "/../includes/network/database.php");
require_once(dirname(__FILE__) . "/../includes/function.php");

checkUserLoggedIn();

$db = new Database();
$remarks = $db->getRemarks();
$internalBusinessTypes = $db->getInternalBusinessTypes();


if (!empty($_POST['retirement'])) {
    $latestAttendanceId = $db->getLatestAttendanceIdBy($_SESSION['user_id']);
    if (!is_null($latestAttendanceId)) {
        $db->createRetirement(
            $latestAttendanceId, $_POST['retirement_hours'],
            $_POST['retirement_minutes'], $_POST['comment'], $_POST['remark'],
            $_POST['internal_business_type']
        );
        header("Location: /attendance_management/index.php");
    }
}


require_once(dirname(__FILE__) . "/../includes/template-parts/header.php");

?>

<section id="retirement-post">
    <div class="inner">
        <div class="form-wrapper">
            <h2 class="title-font">ユーザーの登録画面</h2>
            <form method="POST">
                <p class="subtitle-font">時間</p>
                <div class="content-between">
                    <div class="reset-select-style form-select form-select-half">
                        <select name="retirement_hours" required>
                            <option value="" hidden>時</option>
                            <?php for ($i = 1; $i <= 24; $i++): ?>
                                <option value="<?php echo sprintf('%02d', $i); ?>"><?php echo sprintf('%02d', $i); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <p class="attendance-time-label">：</p>
                    <div class="reset-select-style form-select form-select-half">
                        <select name="retirement_minutes" required>
                            <option value="" hidden>分</option>
                            <?php for ($i = 0; $i < 60; $i = $i + 5): ?>
                                <option value="<?php echo sprintf('%02d', $i) ?>"><?php echo sprintf('%02d', $i); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="content-between mobile-content-wrap">
                    <div class="left">
                        <p class="subtitle-font">備考</p>
                        <div class="reset-select-style form-select form-select-half">
                            <select name="remark">
                                <option value="" hidden>選択</option>
                                <?php foreach ($remarks as $remark): ?>
                                    <option value="<?php echo $remark['id']; ?>"><?php echo $remark['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="right">
                        <p class="subtitle-font">社内業務内容</p>
                        <div class="reset-select-style form-select form-select-half">
                            <select name="internal_business_type">
                                <option value="" hidden>選択</option>
                                <?php foreach ($internalBusinessTypes as $internalBusinessType): ?>
                                    <option value="<?php echo $internalBusinessType['id']; ?>"><?php echo $internalBusinessType['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <p class="subtitle-font">備考詳細</p>
                <textarea class="form-textarea" name="comment" type="textarea"></textarea>
                <div class="for-center">
                    <input class="form-button subtitle-font" type="submit" value="退社" name="retirement">
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once(dirname(__FILE__) . "/../includes/template-parts/footer.php"); ?>
