<?php
// Initialize the session
session_start();

// Include database connection
require_once "config.php";

// Check if post ID is provided
if (isset($_GET['post_id']) && !empty($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    // Fetch post details
    $sql = "SELECT posts.id, posts.title, posts.content, posts.likes, posts.dislikes, posts.user_id, users.username FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $post_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $post_id, $title, $content, $likes, $dislikes, $user_id, $username);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Fetch comments
    $sql_comments = "SELECT comments.id, comments.comment, comments.created_at, comments.likes, comments.dislikes, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = ?";
    $comments = [];
    if ($stmt_comments = mysqli_prepare($conn, $sql_comments)) {
        mysqli_stmt_bind_param($stmt_comments, "i", $post_id);
        mysqli_stmt_execute($stmt_comments);
        mysqli_stmt_bind_result($stmt_comments, $comment_id, $comment_content, $comment_created_at, $comment_likes, $comment_dislikes, $comment_username);
        while (mysqli_stmt_fetch($stmt_comments)) {
            $comments[] = [
                'id' => $comment_id,
                'content' => $comment_content,
                'created_at' => $comment_created_at,
                'likes' => $comment_likes,
                'dislikes' => $comment_dislikes,
                'username' => $comment_username,
            ];
        }
        mysqli_stmt_close($stmt_comments);
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
} else {
    // Redirect to home page if no post ID is provided
    header("location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Details</title>
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
    nav {
        width: 1200px;
        height: 150px;
        background-image: url('./images/story-logo.png');
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light w-100" aria-label="breadcrumb">
        <ol class="breadcrumb ml-5" style="font-size: 25px;">
            <li class="breadcrumb-item"><a href="welcome.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Posts</li>
        </ol>
    </nav>

    <div class="container mt-5">
        <?php
        if (isset($_SESSION['message']) && isset($_SESSION['message_type'])) {
            echo '<div class="alert alert-' . htmlspecialchars($_SESSION['message_type']) . '">' . htmlspecialchars($_SESSION['message']) . '</div>';
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>
        <div class="card">
            <div class="card-body">
                <h3 class="card-title"><?php echo htmlspecialchars($title); ?></h3>
                <p class="card-text"><?php echo nl2br(htmlspecialchars_decode($content)); ?></p>
                <p class="card-text"><small class="text-muted">Posted by <?php echo htmlspecialchars($username); ?></small></p>
                <p class="card-text"><i class="fas fa-thumbs-up" style="color: green;"></i> <?php echo htmlspecialchars($likes); ?>  <i class="fas fa-thumbs-down" style="color: red;"></i> <?php echo htmlspecialchars($dislikes); ?></p>
                <form action="like.php" method="post" style="display: inline;">
                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                    <input type="hidden" name="referrer" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
                    <button type="submit" class="btn btn-primary">Like</button>
                </form>
                <!-- <form action="dislike.php" method="post" style="display: inline;">
                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                    <button type="submit" class="btn btn-danger">Dislike</button>
                </form> -->
            </div>
        </div>
        <div class="mt-4">
            <h4>Comments</h4>
            <?php foreach ($comments as $comment): ?>
                <div class="card mb-3 border border-primary">
                    <div class="card-body">
                        <p class="card-text"><?php echo htmlspecialchars($comment['content']); ?></p>
                        <p class="card-text"><small class="text-muted">Posted by <?php echo htmlspecialchars($comment['username']); ?> on <?php echo htmlspecialchars($comment['created_at']); ?></small></p>
                        <div class="d-flex">
                            <!-- Like button -->
                            <form action="like_comment.php" method="post" class="mr-2">
                                <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                                <button type="submit" class="btn btn-link p-0"><i class="fas fa-thumbs-up" style="color: green;"></i> </button>
                            </form>
                            <!-- Dislike button -->
                            <!-- <form action="dislike_comment.php" method="post" class="mr-2">
                                <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                                <button type="submit" class="btn btn-link p-0"><i class="fas fa-thumbs-down" style="color: red;"></i> <?php echo htmlspecialchars($comment['dislikes']); ?></button>
                            </form> -->
                            <!-- Reply button -->
                            <button class="btn btn-link p-0 reply-button" data-comment-id="<?php echo $comment['id']; ?>"><i class="fas fa-reply" style="color: blue;"></i></button>
                        </div>
                        <!-- Reply form -->
                        <form action="reply.php" method="post" class="mt-3 reply-form" id="reply-form-<?php echo $comment['id']; ?>" style="display: none;">
                            <div class="form-group">
                                <label for="reply_<?php echo $comment['id']; ?>">Reply:</label>
                                <textarea name="reply" id="reply_<?php echo $comment['id']; ?>" class="form-control" rows="2" required></textarea>
                            </div>
                            <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                            <button type="submit" class="btn btn-primary mt-2">Reply</button>
                        </form>
                    </div>
                </div>

                <!-- Display replies for this comment -->
                <?php
                $sql_replies = "SELECT id, content, created_at, user_id FROM comment_replies WHERE comment_id = ?";
                if ($stmt_replies = mysqli_prepare($conn, $sql_replies)) {
                    mysqli_stmt_bind_param($stmt_replies, "i", $comment['id']);
                    mysqli_stmt_execute($stmt_replies);
                    mysqli_stmt_bind_result($stmt_replies, $reply_id, $reply_content, $reply_created_at, $reply_user_id);
                    while (mysqli_stmt_fetch($stmt_replies)) {
                        echo '<div class="reply offset-1">';
                        echo '<p class="reply-text">' . htmlspecialchars($reply_content) . '</p>';
                        echo '<p class="reply-info"><small class="text-muted">Posted by User ' . $reply_user_id . ' on ' . htmlspecialchars($reply_created_at) . '</small></p>';
                        echo '</div>';
                    }
                    mysqli_stmt_close($stmt_replies);
                }
                ?>
            <?php endforeach; ?>

            <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                <form action="comment.php" method="post" class="mt-3">
                    <div class="form-group">
                        <label for="comment">Add a comment:</label>
                        <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
                    </div>
                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </form>
            <?php else: ?>
                <p class="mt-3">You need to <a href="login.php">login</a> to add a comment.</p>
            <?php endif; ?>
        </div>
    </div>
    <script src="./assets/bootstrap/js/bootstrap.min.js"></script>
    <script>
        document.querySelectorAll('.reply-button').forEach(button => {
            button.addEventListener('click', () => {
                const commentId = button.getAttribute('data-comment-id');
                const replyForm = document.getElementById('reply-form-' + commentId);
                replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>
