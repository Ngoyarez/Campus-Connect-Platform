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
$tag_name = "";
$tag_name_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate tag name
    if(empty(trim($_POST["tag_name"]))) {
        $tag_name_err = "Please enter a tag name.";
    } else {
        $tag_name = trim($_POST["tag_name"]);
    }

    // Check input errors before inserting in database
    if(empty($tag_name_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO tags (name) VALUES (?)";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_tag_name);
            
            // Set parameters
            $param_tag_name = $tag_name;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)) {
                // Tag added successfully. Redirect to tags management page
                header("location: tags.php");
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
    <title>Manage Tags</title>
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
                        <h2 class="mt-5">Manage Tags</h2>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label>Tag Name</label>
                                <input type="text" name="tag_name" class="form-control <?php echo (!empty($tag_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $tag_name; ?>">
                                <span class="invalid-feedback"><?php echo $tag_name_err;?></span>
                            </div>
                            <input type="submit" class="btn btn-primary" value="Add Tag">
                        </form>
                        <hr>
                        <h2 class="mt-5">Existing Tags</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch tags from database
                                $sql = "SELECT * FROM tags";
                                if($result = mysqli_query($conn, $sql)) {
                                    if(mysqli_num_rows($result) > 0) {
                                        while($row = mysqli_fetch_array($result)) {
                                            echo "<tr>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . $row['name'] . "</td>";
                                            echo "<td>";
                                            echo "<a href='edit_tag.php?id=". $row['id'] ."' class='btn btn-primary'>Edit</a>";
                                            echo "<a href='delete_tag.php?id=". $row['id'] ."' class='btn btn-danger ml-2'>Delete</a>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                        mysqli_free_result($result);
                                    } else {
                                        echo "<tr><td colspan='3'>No tags found.</td></tr>";
                                    }
                                } else {
                                    echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
                                }

                                // Close connection
                                mysqli_close($conn);
                                ?>
                            </tbody>
                        </table>
                        <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
                    </div>
                </div>        
            </div>
        </div>
    </div>
</body>
</html>