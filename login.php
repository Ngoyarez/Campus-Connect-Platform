<?php
// Initialize the session
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is already logged in, if yes then redirect them to the appropriate page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if ($_SESSION["role"] === "admin") {
        header("location: ./admin/dashboard.php");
    } else {
        header("location: welcome.php");
    }
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username_or_email = $password = "";
$username_or_email_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username/email is empty
    if(empty(trim($_POST["username"]))){
        $username_or_email_err = "Please enter username or email.";
    } else{
        $username_or_email = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_or_email_err) && empty($password_err)){
        // Prepare a select statement to check admin credentials
        $sql = "SELECT id, username, password FROM admin WHERE username = ? OR email = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username_or_email, $param_username_or_email);
            
            // Set parameters
            $param_username_or_email = $username_or_email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username/email exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["role"] = "admin";
                            
                            // Redirect user to admin dashboard
                            header("location: ./admin/dashboard.php");
                            exit();
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username/Email doesn't exist in admin table, check in users table
                    $sql_user = "SELECT id, username, password, profile_picture, profile_completed FROM users WHERE username = ? OR email = ?";
                    
                    if($stmt_user = mysqli_prepare($conn, $sql_user)){
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt_user, "ss", $param_username_or_email, $param_username_or_email);
                        
                        // Attempt to execute the prepared statement
                        if(mysqli_stmt_execute($stmt_user)){
                            // Store result
                            mysqli_stmt_store_result($stmt_user);
                            
                            // Check if username/email exists, if yes then verify password
                            if(mysqli_stmt_num_rows($stmt_user) == 1){                    
                                // Bind result variables
                                mysqli_stmt_bind_result($stmt_user, $id, $username, $hashed_password, $profile_picture, $profile_completed);
                                if(mysqli_stmt_fetch($stmt_user)){
                                    if(password_verify($password, $hashed_password)){
                                        // Password is correct, so start a new session
                                        
                                        // Store data in session variables
                                        $_SESSION["loggedin"] = true;
                                        $_SESSION["id"] = $id;
                                        $_SESSION["username"] = $username;
                                        $_SESSION["profile_picture"] = $profile_picture;
                                        $_SESSION['profile_completed'] = $profile_completed;
                                        $_SESSION["role"] = "user";
                                        
                                        // Check if profile is completed
                                        if($profile_completed === 1){
                                            // Redirect user to welcome page
                                            header("location: welcome.php");
                                        } else{
                                            // Redirect user to profile creation page
                                            header("location: create_profile.php");
                                        }
                                        exit();
                                    } else{
                                        // Password is not valid, display a generic error message
                                        $login_err = "Invalid username or password.";
                                    }
                                }
                            } else{
                                // Username/Email doesn't exist, display a generic error message
                                $login_err = "Invalid username or password.";
                            }
                        } else{
                            echo "Oops! Something went wrong. Please try again later.";
                        }

                        // Close statement
                        mysqli_stmt_close($stmt_user);
                    }
                }
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
    <title>Login</title>
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ background-color: #D3D3D3; width: 400px; padding: 20px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) }
    </style>
</head>
<body>
    <div class="wrapper">
        <img src="./images/story-logo.png" alt="Logo"  class="text-start">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username / Email Address</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_or_email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username_or_email; ?>">
                <span class="invalid-feedback"><?php echo $username_or_email_err; ?></span>
            </div>    
            <div class="form-group mt-2">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group mt-3">
                <input type="submit" class="btn btn-primary" value="Login">
                <!-- <input type="submit" class="btn btn-primary" value="Forgot Password"> -->
            </div>
            <p class="mt-2">Forgot Your Password? <a href="forgot_password.php">Click Here</a>.</p>
            <p class="mt-2">Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
    <script src="./assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
