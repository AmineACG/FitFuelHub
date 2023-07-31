<?php
session_start();
// Check if the user is not logged in and redirect to the login page if necessary
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dsn = 'mysql:host=localhost;dbname=fitfuelhub_db';
    $username = 'root';
    $password = '';

    try {
        $db = new PDO($dsn, $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get the meal ID from the form data
        $mealId = $_POST['id'];

        // Prepare and execute the DELETE query to delete the custom meal
        $query = "DELETE FROM custom_meal WHERE id = :id AND user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $mealId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        // Redirect back to the recipes page after deletion
        header("Location: recipes.php");
        exit;
    } catch (PDOException $e) {
        echo 'Database Error: ' . $e->getMessage();
        die();
    }
} else {
    // If the request method is not POST, redirect to the recipes page
    header("Location: recipes.php");
    exit;
}
?>
