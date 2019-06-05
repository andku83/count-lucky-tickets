<?php

require_once 'LuckyTickets.php';

$count = null;
$sumToOneDigit = 1;

if ($_POST) {
    $from = $_POST['from'] ?? null;
    $to = $_POST['to'] ?? null;
    $sumToOneDigit = !empty($_POST['sumToOneDigit']);
    $luckyTickets = new LuckyTickets($from, $to, $sumToOneDigit);
    if (!$errors = $luckyTickets->validate()) {
        $count = $luckyTickets->count();
    }

}
?>
<html>
<body>
<div class="form">
    <form action="" method="post">
        <p><label>From: <input type="text" name="from" value="<?= $_POST['from'] ?? LuckyTickets::DEFAULT_FROM ?>"></label></p>
        <p><label>To: <input type="text" name="to" value="<?= $_POST['to'] ?? LuckyTickets::DEFAULT_TO ?>"></label></p>
        <input type="hidden" name="sumToOneDigit" value="0">
        <p><label><input type="checkbox" name="sumToOneDigit" value="1" <?= $sumToOneDigit ? 'checked' : '' ?>>Sum To One Digit </label></p>
        <button>Calculate</button>
    </form>
</div>
<?php if ($_POST): ?>
<div class="errors" style="color: red">
    <?php foreach ($errors as $error): ?>
        <p><?= $error ?></p>
    <?php endforeach; ?>
</div>
<div class="result" ><?= $count ?></div>
<?php endif; ?>
</body>
</html>
