<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['id'];

// Handle profile creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = $_POST['bio'];
    $school = $_POST['school'];

    // Handle profile picture upload
    $profile_picture = null;  // Initialize profile_picture variable
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture = $target_file;
        }
    }

    // Update user data and set profile as completed
    $stmt = $conn->prepare("UPDATE users SET bio = ?, school = ?, profile_picture = ?, profile_completed = TRUE WHERE id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("sssi", $bio, $school, $profile_picture, $user_id);
    if ($stmt->execute() === false) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->close();

    // Redirect to home page
    header('Location: home.php');
    exit();
}

// Fetch existing user data if any
$stmt = $conn->prepare("SELECT profile_picture, bio, school FROM users WHERE id = ?");
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $user_id);
if ($stmt->execute() === false) {
    die('Execute failed: ' . htmlspecialchars($stmt->error));
}
$stmt->bind_result($profile_picture, $bio, $school);
$stmt->fetch();
$stmt->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Profile</title>
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
    <style>
        .profile-picture {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
        nav{
            width: 1200px;
            height: 150px;
            background-image: url('./images/story-logo.png');
            background-repeat: no-repeat;
            background-size: cover;
/*            opacity: 0.7;*/
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light w-100" aria-label="breadcrumb">
      <ol class="breadcrumb ml-5" style="font-size: 25px;">
        <li class="breadcrumb-item"><a href="welcome.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">About</li>
        <!-- <li class="breadcrumb-item active" aria-current="page">Data</li> -->
      </ol>
    </nav>
    
    <div class="container">
        <h2 class="text-center bg-primary text-white">Create Your Profile</h2>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <div class="form-group text-start">
                <label for="profile_picture">Profile Picture</label><br>
                <?php if ($profile_picture): ?>
                    <img src="<?= $profile_picture ?>" alt="Profile Picture" class="profile-picture">
                <?php else: ?>
                    <img src="./images/img_avatar.png" alt="Default Profile Picture" class="profile-picture">
                <?php endif; ?>
                <input type="file" class="form-control-file mt-2" id="profile_picture" name="profile_picture">
            </div>
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea class="form-control" id="bio" name="bio" rows="4"><?= htmlspecialchars($bio) ?></textarea>
            </div>
            <div class="form-group">
                <label for="website">University Studied</label>
                <input type="text" class="form-control" id="school" name="school" value="<?= htmlspecialchars($school) ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Complete Profile</button>
        </form>
    </div>
<script src="./assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
