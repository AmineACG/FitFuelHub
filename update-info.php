<?php
// update-info.php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Retrieve user data from the database
    $query = "SELECT * FROM user WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the new weight and height from the form
    $weight = isset($_POST['weight']) ? $_POST['weight'] : $user['weight'];
    $height = isset($_POST['height']) ? $_POST['height'] : $user['height'];

    // Update the user's information in the database
    $query = "UPDATE user SET weight = :weight, height = :height WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->execute([':weight' => $weight, ':height' => $height, ':user_id' => $_SESSION['user_id']]);

    // Redirect back to the profile page after the update
    header("Location: profile.php");
    exit();
}
?>
