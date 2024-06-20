<?php
// Initialize the session
session_start();

// Include database connection
require_once "config.php";

// Check if query is provided
if (isset($_GET['query']) && !empty($_GET['query']) && isset($_GET['filter'])) {
    $query = "%" . $_GET['query'] . "%";
    $filter = $_GET['filter'];

    switch ($filter) {
        case 'title':
            $sql = "SELECT posts.id, posts.title, posts.content, posts.created_at, users.username, users.school AS school FROM posts 
                    JOIN users ON posts.user_id = users.id
                    -- JOIN schools ON users.school_id = schools.id
                    WHERE posts.title LIKE ?";
            break;
        case 'user':
            $sql = "SELECT posts.id, posts.title, posts.content, posts.created_at, users.username, users.school AS school FROM posts 
                    JOIN users ON posts.user_id = users.id
                    -- JOIN schools ON users.school_id = schools.id
                    WHERE users.username LIKE ?";
            break;
        case 'time':
            $sql = "SELECT posts.id, posts.title, posts.content, posts.created_at, users.username, users.school AS school FROM posts 
                    JOIN users ON posts.user_id = users.id
                    -- JOIN schools ON users.school_id = schools.id
                    WHERE posts.created_at LIKE ?";
            break;
        case 'school':
            $sql = "SELECT posts.id, posts.title, posts.content, posts.created_at, users.username, users.school AS school FROM posts 
                    JOIN users ON posts.user_id = users.id
                    -- JOIN schools ON users.school_id = schools.id
                    WHERE users.school LIKE ?";
            break;
        case 'category':
            $sql = "SELECT posts.id, posts.title, posts.content, posts.created_at, users.username, users.school AS school FROM posts 
                    JOIN users ON posts.user_id = users.id
                    -- JOIN schools ON users.school_id = schools.id
                    WHERE posts.category LIKE ?";
            break;
        default:
            $sql = "";
            break;
    }

    if ($sql) {
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $query);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $post_id, $title, $content, $created_at, $username, $school, $category);
            $results = [];
            while (mysqli_stmt_fetch($stmt)) {
                $results[] = [
                    'id' => $post_id,
                    'title' => $title,
                    'content' => $content,
                    'created_at' => $created_at,
                    'username' => $username,
                    'school' => $school,
                    'category' => $category,
                ];
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
} else {
    // Redirect to home page if no query is provided
    header("location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Search Results</h2>
        <?php if (!empty($results)): ?>
            <div class="list-group">
                <?php foreach ($results as $post): ?>
                    <a href="post_details.php?post_id=<?php echo htmlspecialchars($post['id']); ?>" class="list-group-item list-group-item-action">
                        <h5 class="mb-1"><?php echo htmlspecialchars($post['title']); ?></h5>
                        <p class="mb-1"><?php echo htmlspecialchars(substr($post['content'], 0, 150)) . '...'; ?></p>
                        <small>Posted by <?php echo htmlspecialchars($post['username']); ?> from <?php echo htmlspecialchars($post['school']); ?> on <?php echo htmlspecialchars($post['created_at']); ?></small>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No results found for your search query.</p>
        <?php endif; ?>
    </div>
</body>
</html>
