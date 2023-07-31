<?php
session_start();

// Check if the user is already logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Connect to the database
$dsn = 'mysql:host=localhost;dbname=fitfuelhub_db';
$username = 'root';
$password = '';

try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Database Error: ' . $e->getMessage();
    die();
}

// Check if a file was uploaded
if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] === UPLOAD_ERR_NO_FILE) {
    echo '<script>alert("Please select a file to upload.");</script>';
    exit();
}
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    // Check if the uploaded file is an image
    $mime_type = mime_content_type($_FILES['profile_picture']['tmp_name']);
    $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($mime_type, $allowed_mime_types)) {
        echo '<script>alert("Invalid file format. Please upload a JPEG, PNG, or GIF image."); window.location.href = "profile.php";</script>';
        exit();
    }

    // Move the uploaded file to a directory on the server
    $upload_dir = 'images/profiles/';
    $filename = $_SESSION['user_id'] . '_' . time() . '_' . $_FILES['profile_picture']['name'];
    $destination = $upload_dir . $filename;

    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination)) {
        // Update the user's profile picture in the database
        $query = "UPDATE user SET profile_picture = :profile_picture WHERE user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->execute([':profile_picture' => $filename, ':user_id' => $_SESSION['user_id']]);
    
        // Redirect to the profile page after successful upload
        header("Location: profile.php");
        exit();
    } else {
        echo '<script>alert("Failed to upload profile picture."); window.location.href = "profile.php";</script>';
        exit();
    }
} else {
    echo "No file was uploaded or an error occurred during the upload.";
    exit();
}
?>
