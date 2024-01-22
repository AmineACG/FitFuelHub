<?php
// update_activity_level.php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize the input (you can add more validation here if needed)
    $activityLevel = filter_input(INPUT_POST, 'activity_level', FILTER_VALIDATE_FLOAT);

    if ($activityLevel === false) {
        echo json_encode(['success' => false, 'message' => 'Invalid activity level']);
        exit();
    }

    // Update the activity level in the database for the current user
    // Assuming you have a table named 'user' with a column named 'activity_level'
    $dsn = 'mysql:host=localhost;dbname=fitfuelhub_db';
    $username = 'root';
    $password = '';

    try {
        $db = new PDO($dsn, $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute the update query
        $query = "UPDATE user SET activity_level = :activity_level WHERE user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->execute([':activity_level' => $activityLevel, ':user_id' => $_SESSION['user_id']]);

        // Respond with a success message
        echo json_encode(['success' => true, 'message' => 'Activity level updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
