<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
    header("location: ../login.php");
    exit();
}

if (isset($_POST['send'])) {
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Get all user emails
    $sql = "SELECT email FROM users";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        mail($row['email'], $subject, $message);
    }
    echo "Notifications sent.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Send Notifications</title>
</head>
<body>
    <h1>Send Notifications</h1>
    <form method="post">
        <label>Subject</label>
        <input type="text" name="subject"><br>
        <label>Message</label>
        <textarea name="message"></textarea><br>
        <input type="submit" name="send" value="Send">
    </form>
</body>
</html>