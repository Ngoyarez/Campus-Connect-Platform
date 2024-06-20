<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
</head>
<style>
    nav{
            width: 1200px;
            height: 150px;
        }
</style>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light w-100" aria-label="breadcrumb">
      <ol class="breadcrumb ml-5">
        <li class="breadcrumb-item"><a href="welcome.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Posts</li>
        <!-- <li class="breadcrumb-item active" aria-current="page">Data</li> -->
      </ol>
    </nav>

    <div class="container">
        <div class="row mt-5 ptb-100 gap-3">
            <?php
            // Include database connection
            require_once "config.php";

            // SQL query to fetch posts
            $sql = "SELECT * FROM posts";
            $result = mysqli_query($conn, $sql);

            // Check if there are posts
            if (mysqli_num_rows($result) > 0) {
                // Loop through each row
                while ($row = mysqli_fetch_assoc($result)) {
                    // Truncate content if it's longer than a certain length
                    $content = $row['content'];
                    $truncated_content = (strlen($content) > 100) ? substr($content, 0, 100) . "..." : $content;

                    // Output card for each post
                    ?>
                    <div class="card col-md-4" style="width: 18rem;">
                        <img class="card-img-top" src="<?php echo $row['post_image']; ?>" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['title']; ?></h5>
                            <p class="card-text" id="content_<?php echo $row['id']; ?>"><?php echo $truncated_content; ?></p>
                            <?php if (strlen($content) > 100) { ?>
                                <a href="post_details.php?post_id=<?php echo $row['id']; ?>" class="btn btn-primary read-more-btn" data-postid="<?php echo $row['id']; ?>">Read More</a>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // If no posts found
                echo "<p>No posts found.</p>";
            }

            // Close database connection
            mysqli_close($conn);
            ?>
        </div>
    </div>

    <!-- <script>
        // JavaScript to toggle full content on "Read More" button click
        document.querySelectorAll('.read-more-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const postId = this.getAttribute('data-postid');
                const contentElem = document.getElementById(`content_${postId}`);
                const fullContent = contentElem.dataset.fullcontent;
                contentElem.textContent = fullContent;
                this.style.display = 'none';
            });
        });
    </script> -->
</body>
</html>
