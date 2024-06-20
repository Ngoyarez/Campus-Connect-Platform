<?php
// Initialize the session
session_start();

// Include config file
require_once "config.php";

// Check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Check if post ID and comment are provided
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_id']) && !empty(trim($_POST['comment']))) {
    // Prepare SQL statement to insert comment
    $sql = "INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)";
    
    if($stmt = mysqli_prepare($conn, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "iis", $param_post_id, $param_user_id, $param_comment);
        
        // Set parameters
        $param_post_id = $_POST['post_id'];
        $param_user_id = $_SESSION['id'];
        $param_comment = trim($_POST['comment']);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Redirect back to the post details page after commenting
            header("location: post_details.php?post_id=" . $param_post_id);
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
} else {
    echo "Invalid request.";
}

// Close connection
mysqli_close($conn);
?>
