<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <?php include 'sidebar.php'; ?>
        </div>

        <div class="content">
            <!-- Header -->
            <?php include 'header.php'; ?>   

            <!-- Main Content -->
            <div class="container mt-5">
                <h1>Welcome, Admin</h1>
                <?php include 'analytics.php'; ?>
            </div>

           
        </div>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
