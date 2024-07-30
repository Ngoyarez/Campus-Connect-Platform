<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
    header("location: ../login.php");
    exit();
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "UPDATE posts SET title = ?, content = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $title, $content, $id);
    mysqli_stmt_execute($stmt);
    header("location: posts.php");
}

$id = $_GET['id'];
$sql = "SELECT * FROM posts WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$post = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <?php include 'sidebar.php'; ?>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h1>Edit Post</h1>
                        <form method="post">
                            <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                            <label>Title</label>
                            <input type="text" name="title" value="<?php echo $post['title']; ?>"><br>
                            <label>Content</label>
                            <textarea name="content" cols="30"><?php echo $post['content']; ?></textarea><br>
                            <input type="submit" name="update" value="Update">
                        </form>
                    </div>
                </div>        
            </div>
        </div>
    </div>
</body>
</html>