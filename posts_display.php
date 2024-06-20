<?php
// Initialize the session
session_start();

// Include config file
require_once "config.php";

// Fetch posts from the database
$sql = "SELECT posts.id, posts.title, posts.content, posts.post_image, posts.likes, posts.user_id, users.username, users.school FROM posts JOIN users ON posts.user_id = users.id WHERE posts.approved = 1 ORDER BY posts.created_at DESC";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Posts</title>
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
    <style>
        .card-img-top {
            width: 100%;
            height: 15vw;
            object-fit: cover;
        }
        nav{
            width: 1200px;
            height: 150px;
            background-image: url('./images/story-logo.png');
            background-repeat: no-repeat;
            background-size: cover;
/*            opacity: 0.7;*/
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light w-100" aria-label="breadcrumb">
      <ol class="breadcrumb ml-5" style="font-size: 25px;">
        <li class="breadcrumb-item"><a href="welcome.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Posts</li>
        <!-- <li class="breadcrumb-item active" aria-current="page">Data</li> -->
      </ol>
    </nav>

    <div class="container">
        <div class="row mt-5 ptb-100 gap-3">
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="card col-md-4" style="width: 18rem;">
                    <img class="card-img-top" src="<?php echo $row['post_image']; ?>" alt="Post Image">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                        <p class="card-text">
                            <?php 
                                $content = htmlspecialchars($row['content']); 
                                echo strlen($content) > 100 ? substr($content, 0, 100) . '...' : $content;
                            ?>
                        </p>
                        <span class="text-muted">Posted by <?php echo htmlspecialchars($row['username']); ?> from <?php echo htmlspecialchars($row['school']); ?></span>
                        <p class="card-text">Likes: <?php echo htmlspecialchars($row['likes']); ?></p>
                        <a href="post_details.php?post_id=<?php echo $row['id']; ?>" class="btn btn-primary">Read More</a>
                        <a href="like.php?post_id=<?php echo $row['id']; ?>" class="btn btn-success">Like</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script src="./assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
