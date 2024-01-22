<?php
session_start();
// Check if the user is not logged in and redirect to the login page if necessary
if (!isset($_SESSION['user_id'])) {
    header("Location: SignUp.php");
    exit();
}

// Retrieve form data
$dsn = 'mysql:host=localhost;dbname=fitfuelhub_db';
$username = 'root';
$password = '';

try {
    // Connect to the database
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Retrieve the food table with nutriment value

} catch (PDOException $e) {
    // Handle any database errors
    echo 'Database Error: ' . $e->getMessage();
    die();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $mealName = $_POST['meal_name'];
    $calories = $_POST['calories'];
    $protein = $_POST['protein'];
    $carbohydrates = $_POST['carbohydrates'];
    $fat = $_POST['fat'];

    // Validate form data (you can add more validation if needed)
    if (empty($mealName) || empty($calories) || empty($protein) || empty($carbohydrates) || empty($fat)) {
        $error = "All fields are required.";
    } else {
        // Insert custom meal data into the database
        $query = "INSERT INTO favorite_meal (meal_name, user_id, calories, protein, carbohydrates, fat, created_at) VALUES (:meal_name, :user_id, :calories, :protein, :carbohydrates, :fat, NOW())";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':meal_name' => $mealName,
            ':user_id' => $_SESSION['user_id'],
            ':calories' => $calories,
            ':protein' => $protein,
            ':carbohydrates' => $carbohydrates,
            ':fat' => $fat
        ]); 
        $query = "UPDATE custom_meal SET favorite = 1 where user_id = :user_id and meal_name = :meal_name";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':meal_name' => $mealName,
            ':user_id' => $_SESSION['user_id'],
        ]);
        header('Location: recipes.php');
    }
}
?>