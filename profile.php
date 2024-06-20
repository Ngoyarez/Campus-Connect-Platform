<?php
session_start();
require 'db_connection.php'; // Include database connection file

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT username, email, profile_picture, bio, website FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $profile_picture, $bio, $website);
$stmt->fetch();
$stmt->close();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_bio = $_POST['bio'];
    $new_website = $_POST['website'];

    // Handle profile picture upload
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
        $profile_picture = $target_file;
    }

    // Update user data
    $stmt = $conn->prepare("UPDATE users SET bio = ?, website = ?, profile_picture = ? WHERE id = ?");
    $stmt->bind_param("sssi", $new_bio, $new_website, $profile_picture, $user_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to profile page
    header('Location: profile.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center">Your Profile</h2>
    <form action="profile.php" method="post" enctype="multipart/form-data">
        <div class="form-group text-center">
            <label for="profile_picture">Profile Picture:</label><br>
            <?php if ($profile_picture): ?>
                <img src="<?= $profile_picture ?>" alt="Profile Picture" class="profile-picture">
            <?php else: ?>
                <img src="default-profile.png" alt="Default Profile Picture" class="profile-picture">
            <?php endif; ?>
            <input type="file" class="form-control-file mt-2" id="profile_picture" name="profile_picture">
        </div>
        <div class="form-group">
            <label for="bio">Bio:</label>
            <textarea class="form-control" id="bio" name="bio" rows="4"><?= htmlspecialchars($bio) ?></textarea>
        </div>
        <div class="form-group">
            <label for="website">Website:</label>
            <input type="url" class="form-control" id="website" name="website" value="<?= htmlspecialchars($website) ?>">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
    </form>
</div>
</body>
</html>
