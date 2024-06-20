<?php
// Initialize the session
session_start();

// Include PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader (if using Composer)
require 'vendor/autoload.php';

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$email = "";
$email_err = "";
$reset_success = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate email address
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email address.";     
    } else {
        $email = trim($_POST["email"]);
    }

    // Check input errors before updating the database
    if(empty($email_err)){
        // Prepare a select statement to check if email exists
        $sql = "SELECT id FROM users WHERE email = ?";

        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Email exists, generate a token and send email
                    $token = bin2hex(random_bytes(50)); // Generate a random token
                    $expiry = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token expiry time

                    // Prepare an insert statement to store the token
                    $sql = "INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)";

                    if($stmt = mysqli_prepare($conn, $sql)){
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "sss", $param_email, $param_token, $param_expiry);
                        
                        // Set parameters
                        $param_email = $email;
                        $param_token = $token;
                        $param_expiry = $expiry;

                        // Attempt to execute the prepared statement
                        if(mysqli_stmt_execute($stmt)){
                            // Send the email with the reset link using PHPMailer
                            $reset_link = "http://localhost/Campus/reset-password.php?token=$token";
                            
                            $mail = new PHPMailer(true); // Passing `true` enables exceptions

                            try {
                                // Server settings
                                $mail->SMTPDebug = 0;                               // Enable verbose debug output
                                $mail->isSMTP();                                    // Set mailer to use SMTP
                                $mail->Host       = 'smtp.gmail.com';               // Specify main and backup SMTP servers
                                $mail->SMTPAuth   = true;                           // Enable SMTP authentication
                                $mail->Username   = 'brianngoya3667@gmail.com';     // SMTP username
                                $mail->Password   = 'odev voaj ygvs hhix';           // SMTP password
                                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `ssl` also accepted
                                $mail->Port       = 587;                            // TCP port to connect to

                                // Recipients
                                $mail->setFrom('no-reply@story.com', 'Mailer');
                                $mail->addAddress($email);                         // Add a recipient

                                // Content
                                $mail->isHTML(true);                                // Set email format to HTML
                                $mail->Subject = 'Password Reset Request';
                                $mail->Body    = "Click the following link to reset your password:<br> <a href='$reset_link'>$reset_link</a>";
                                $mail->AltBody = "Click the following link to reset your password: $reset_link";

                                $mail->send();
                                $reset_success = "An email has been sent with password reset instructions.";
                            } catch (Exception $e) {
                                $email_err = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                            }
                        } else {
                            $email_err = "Something went wrong. Please try again later.";
                        }

                        // Close statement
                        mysqli_stmt_close($stmt);
                    }
                } else {
                    $email_err = "No account found with that email address.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
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
    <title>Forgot Password</title>
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ background-color: #D3D3D3; width: 400px; padding: 20px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) }
    </style>
</head>
<body>
    <div class="wrapper">
        <img src="./images/story-logo.png" alt="Logo" style="max-width: 200px; margin-bottom: 20px;">
        <h2>Forgot Password</h2>
        <p>Please fill out this form to reset your password.</p>
        <?php 
        if(!empty($reset_success)){
            echo '<div class="alert alert-success">' . $reset_success . '</div>';
        }        
        if(!empty($email_err)){
            echo '<div class="alert alert-danger">' . $email_err . '</div>';
        }        
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>

            <div class="form-group mt-3">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="login.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>
