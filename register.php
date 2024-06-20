<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email address.";     
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 12){
        $password_err = "Password must have at least 12 characters, including uppercase, lowercase, and special characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/font-awesome/css/font-awesome.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ background-color: #D3D3D3; width: 400px; padding: 20px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) }
        .strength-meter { margin-top: 10px; display: none; }
        .strength-meter .progress-bar { min-width: 0; }
    </style>
</head>
<body>
    <div class="wrapper justify-content-center text-start">
        <img src="./images/story-logo.png" alt="Logo">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>   
            <div class="form-group mt-2">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div> 
            <div class="form-group mt-2">
                <label>Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                    <div class="input-group-append" id="togglePasswordContainer" style="display: none;">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye" id="togglePassword"></i>
                        </button>
                    </div>
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                
                <div id="password-strength-meter" class="strength-meter">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar"></div>
                    </div>
                    <small id="password-strength-message"></small>
                </div>
            </div>
            <div class="form-group mt-2">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group mt-3">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p class="mt-2">Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    

    <script src="./assets/jquery/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            var passwordInput = $('#password');
            var togglePasswordButton = $('#togglePassword');
            var togglePasswordContainer = $('#togglePasswordContainer');
            var meter = $('#password-strength-meter .progress-bar');
            var strengthMessage = $('#password-strength-message');

            passwordInput.focus(function () {
                togglePasswordContainer.show();
                $('#password-strength-meter').show();
            });

            passwordInput.blur(function () {
                togglePasswordContainer.hide();
            });

            togglePasswordButton.click(function (passwordField ) {
                var passwordField = passwordInput.get(0);
                var type = passwordField.attr('type');
                var newFieldType = (type === 'password') ? 'text' : 'password'
                passwordField.setAttribute('type', newFieldType);
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            });

            passwordInput.on('input', function (event) {
                event.preventDefault();
                var value = passwordInput.val();
                var strength = 0;
                if (value.length === 0) {
                    meter.css('width', '0').removeClass('bg-warning bg-info bg-success').addClass('bg-danger');
                    strengthMessage.text('Please enter password');
                    return;
                }
                if (value.length > 12 && value.match(/[$@$!%*?&]+/)) {
                    strength += 1;
                }
                if (value.match(/[a-z]+/)) {
                    strength += 1;
                }
                if (value.match(/[A-Z]+/)) {
                    strength += 1;
                }
                if (value.match(/[0-9]+/)) {
                    strength += 1;
                }
                if (value.match(/[$@$!%*?&]+/)) {
                    strength += 1;
                }

                switch (strength) {
                    case 0:
                    case 1:
                        meter.css('width', '20%').removeClass('bg-warning bg-info bg-success').addClass('bg-danger');
                        strengthMessage.text('Weak password');
                        break;
                    case 2:
                        meter.css('width', '40%').removeClass('bg-danger bg-info bg-success').addClass('bg-warning');
                        strengthMessage.text('Average password');
                        break;
                    case 3:
                        meter.css('width', '60%').removeClass('bg-danger bg-warning bg-success').addClass('bg-info');
                        strengthMessage.text('Strong password');
                        break;
                    case 4:
                        meter.css('width', '80%').removeClass('bg-danger bg-warning bg-info').addClass('bg-success');
                        strengthMessage.text('Very strong password');
                        break;
                    case 5:
                        meter.css('width', '100%').removeClass('bg-danger bg-warning bg-info').addClass('bg-success');
                        strengthMessage.text('Very strong password');
                        break;
                    default:
                        meter.css('width', '0').removeClass('bg-danger bg-warning bg-info').addClass('bg-danger');
                        strengthMessage.text('');
                }
            });
        });
    </script>
    <script src="./assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
