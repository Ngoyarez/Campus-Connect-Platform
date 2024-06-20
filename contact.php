<?php
// Include database connection
require_once "config.php";

// Define variables and initialize with empty values
$name = $email = $message = "";
$name_err = $email_err = $message_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email address.";
    } else {
        $email = trim($_POST["email"]);
        // Check if email address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email format.";
        }
    }

    // Validate message
    if (empty(trim($_POST["message"]))) {
        $message_err = "Please enter your message.";
    } else {
        $message = trim($_POST["message"]);
    }

    // Check input errors before inserting into database
    if (empty($name_err) && empty($email_err) && empty($message_err)) {
        // Prepare a SQL statement to insert the message into the database
        $sql = "INSERT INTO messages (name, email, message) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_email, $param_message);

            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_message = $message;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to a success page or display a success message
                header("location: contact_success.php");
                exit();
            } else {
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
    <title>About Us</title>
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
</head>
<style>
    nav{
    width: 1200px;
    height: 150px;
    background-image: url('./images/story-logo.png');
    background-repeat: no-repeat;
    background-size: cover;
    /*  opacity: 0.7;*/
    }

</style>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light w-100" aria-label="breadcrumb">
      <ol class="breadcrumb ml-5" style="font-size: 25px;">
        <li class="breadcrumb-item"><a href="welcome.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Contact</li>
        <!-- <li class="breadcrumb-item active" aria-current="page">Data</li> -->
      </ol>
    </nav>

    <div class="container">
        <h2>Contact Us</h2>
        <form id="contactForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
                <!-- <input type="text" class="form-control" id="name" name="name"> -->
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
                <!-- <input type="email" class="form-control" id="email" name="email"> -->
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" class="form-control <?php echo (!empty($message_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $message; ?>"></textarea>
                <span class="invalid-feedback"><?php echo $message_err; ?></span>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Bootstrap JS and jQuery (needed for client-side validation) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Client-side validation script -->
    <script>
        $(document).ready(function() {
            // Client-side form validation using jQuery
            $('#contactForm').submit(function(event) {
                // Prevent the form from submitting
                event.preventDefault();
                // Perform client-side validation
                var name = $('#name').val().trim();
                var email = $('#email').val().trim();
                var message = $('#message').val().trim();

                if (name === '' || email === '' || message === '') {
                    alert('Please fill in all fields.');
                    return;
                }

                if (!validateEmail(email)) {
                    alert('Please enter a valid email address.');
                    return;
                }

                // If all validations pass, submit the form
                this.submit();
            });

            // Function to validate email address format
            function validateEmail(email) {
                var re = /\S+@\S+\.\S+/;
                return re.test(email);
            }
        });
    </script>

</body>
</html>
