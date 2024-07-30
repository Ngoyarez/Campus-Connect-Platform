<?php
// Initialize the session
session_start();

// Include database connection
require_once "../config.php";

// Check if user is logged in and is admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin") {
    header("location: ../login.php");
    exit();
}

// Define variables and initialize with empty values
$site_name = $site_description = "";
$site_name_err = $site_description_err = "";

// Fetch existing settings from the database
$sql = "SELECT * FROM settings";
$result = mysqli_query($conn, $sql);
$settings = mysqli_fetch_assoc($result);

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate site name
    if(empty(trim($_POST["site_name"]))) {
        $site_name_err = "Please enter the site name.";
    } else {
        $site_name = trim($_POST["site_name"]);
    }

    // Validate site description
    if(empty(trim($_POST["site_description"]))) {
        $site_description_err = "Please enter the site description.";
    } else {
        $site_description = trim($_POST["site_description"]);
    }

    // Check input errors before updating in database
    if(empty($site_name_err) && empty($site_description_err)) {
        // Prepare an update statement
        $sql = "UPDATE settings SET site_name = ?, site_description = ? WHERE id = 1";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_site_name, $param_site_description);
            
            // Set parameters
            $param_site_name = $site_name;
            $param_site_description = $site_description;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)) {
                // Settings updated successfully. Redirect to settings management page
                header("location: settings.php");
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
} else {
    // Set initial values to existing settings
    if ($settings) {
        $site_name = $settings['site_name'];
        $site_description = $settings['site_description'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Settings</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <?php include 'sidebar.php'; ?>
        </div>

        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="mt-5">Manage Settings</h2>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label>Site Name</label>
                                <input type="text" name="site_name" class="form-control <?php echo (!empty($site_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $site_name; ?>">
                                <span class="invalid-feedback"><?php echo $site_name_err;?></span>
                            </div>
                            <div class="form-group mt-3">
                                <label>Site Description</label>
                                <textarea name="site_description" class="form-control <?php echo (!empty($site_description_err)) ? 'is-invalid' : ''; ?>"><?php echo $site_description; ?></textarea>
                                <span class="invalid-feedback"><?php echo $site_description_err;?></span>
                            </div>
                            <input type="submit" class="btn btn-primary mt-3" value="Save Changes">
                        </form>
                        <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
                    </div>
                </div>        
            </div>
        </div>
    </div>
</body>
</html>