<?php
// Initialize the session
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in, if not then redirect them to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$title = $content = $category = "";
$title_err = $content_err = $category_err = $post_success = "";

// Fetch categories from the database
$sql = "SELECT * FROM categories";
$category_result = mysqli_query($conn, $sql);

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate title
    if(empty(trim($_POST["title"]))){
        $title_err = "Please enter a title.";
    } else{
        $title = trim($_POST["title"]);
    }
    
    // Validate content
    if(empty(trim($_POST["content"]))){
        $content_err = "Please enter content.";
    } else{
        $content = trim($_POST["content"]);
    }

    // Validate category
    if(empty(trim($_POST["category"]))){
        $category_err = "Please choose post category.";
    } else{
        $category = trim($_POST["category"]);
    }
    
    // Check input errors before inserting in database
    if(empty($title_err) && empty($content_err) && empty($category_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO posts (user_id, title, content, category) VALUES (?, ?, ?, ?)";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isss", $param_user_id, $param_title, $param_content, $param_category);
            
            // Set parameters
            $param_user_id = $_SESSION["id"];
            $param_title = $title;
            $param_content = $content;
            $param_category = $category;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Post added successfully
                $post_success = "Post submitted successfully! It will be displayed once approved by an admin.";
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Post</title>
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
</head>
<body>
    <div class="container mt-5">
        <?php
        if(!empty($post_success)){
            echo '<div class="alert alert-success">' . $post_success . '</div>';
        }
        ?>
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Add Post</h3>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                        <span class="invalid-feedback"><?php echo $title_err; ?></span>
                    </div>    
                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="content" class="form-control <?php echo (!empty($content_err)) ? 'is-invalid' : ''; ?>"><?php echo $content; ?></textarea>
                        <span class="invalid-feedback"><?php echo $content_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" class="form-control <?php echo (!empty($category_err)) ? 'is-invalid' : ''; ?>">
                            <option value="">Select a category</option>
                            <?php while($row = mysqli_fetch_assoc($category_result)): ?>
                                <option value="<?php echo $row['name']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $category_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>    
    <script>
        CKEDITOR.replace('content');
    </script>
    <script src="./assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
