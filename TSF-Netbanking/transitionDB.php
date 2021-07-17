<?php
require_once "db/dbconn.php";

$sender = $_POST['sender'];
$receiver = $_POST['receiver'];
$amount = $_POST['amount'];
$re_mark = $_POST['re-mark'];

try {
    // sender
    $fetchSenderAmount = "SELECT `bankAmount` FROM `users` WHERE user_id =:sender";
    $SenderAmount = $pdo->prepare($fetchSenderAmount);
    $SenderAmount->bindParam('sender', $sender);
    $SenderAmount->execute();
    $sav = $SenderAmount->fetch();
    if ($sav['bankAmount'] < $amount) {
        echo "<script>alert('balance not enugph')</script>";
        echo "<a href='transition.php'>Try Again</a>";
        // header("Location: customerDetalis.php?id=$sender");
    } else {
        $remainAmount = $sav['bankAmount'] - $amount;
        // echo $remainAmount;
        $sqlSender = "UPDATE `users` SET `bankAmount`=:remainAmount WHERE user_id =:sender";
        $SenderRemainAmount = $pdo->prepare($sqlSender);
        $SenderRemainAmount->bindParam('remainAmount', $remainAmount);
        $SenderRemainAmount->bindParam('sender', $sender);
        $SenderRemainAmount->execute();

        // receiver
        $fetchReceiverAmount = "SELECT `bankAmount` FROM `users` WHERE user_id =:receiver";
        $ReceiverAmount = $pdo->prepare($fetchReceiverAmount);
        $ReceiverAmount->bindParam('receiver', $receiver);
        $ReceiverAmount->execute();
        $rav = $ReceiverAmount->fetch();


        $newAmount = $rav['bankAmount'] + $amount;
        // echo $newAmount;
        $sqlReceiver = "UPDATE `users` SET `bankAmount`=:newAmount WHERE user_id =:receiver";
        $receiverNewAmount = $pdo->prepare($sqlReceiver);
        $receiverNewAmount->bindParam('newAmount', $newAmount);
        $receiverNewAmount->bindParam('receiver', $receiver);
        $receiverNewAmount->execute();

        $sql = "INSERT INTO `transition records`(`sender`, `receiver`, `amount`, `re_mark`) VALUES (:sender, :receiver, :amount, :re_mark )";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam('sender', $sender);
        $stmt->bindParam('receiver', $receiver);
        $stmt->bindParam('amount', $amount);
        $stmt->bindParam('re_mark', $re_mark);
        $stmt->execute();

        header("Location: customerDetalis.php?id=$sender");
    }
} catch (PDOException $th) {
    echo $th->getMessage();
}
