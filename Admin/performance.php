<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
    header("location: ../login.php");
    exit();
}

// Example: Display server load and database status
$load = sys_getloadavg();
$db_status = mysqli_ping($conn) ? "Connected" : "Disconnected";
?>
<!DOCTYPE html>
<html>
<head>
    <title>System Performance</title>
</head>
<body>
    <h1>System Performance</h1>
    <p>Server Load (1 min): <?php echo $load[0]; ?></p>
    <p>Server Load (5 min): <?php echo $load[1]; ?></p>
    <p>Server Load (15 min): <?php echo $load[2]; ?></p>
    <p>Database Status: <?php echo $db_status; ?></p>
</body>
</html>