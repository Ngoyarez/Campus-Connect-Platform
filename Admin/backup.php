<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
    header("location: ../login.php");
    exit();
}

if (isset($_POST['backup'])) {
    // Database credentials
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "campus_project";

    // Create backup
    $backup_file = $db_name . '_backup_' . date('Y-m-d_H-i-s') . '.sql';
    $command = "mysqldump --opt -h $db_host -u $db_user -p$db_pass $db_name > $backup_file";

    system($command);
    echo "Backup completed.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Site Backup</title>
</head>
<body>
    <h1>Site Backup</h1>
    <form method="post">
        <input type="submit" name="backup" value="Backup Now">
    </form>
</body>
</html>