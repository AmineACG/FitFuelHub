<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['meal_name'], $_POST['calories'], $_POST['fat'], $_POST['protein'], $_POST['carbohydrates'])) {
        // Check if the custom_meal cookie exists
        $previousCustomMeal = [];
        if (isset($_COOKIE['custom_meal'])) {
            $previousCustomMeal = json_decode($_COOKIE['custom_meal'], true);
        }

        // New custom meal data
        $customMealData = [
            'meal_name' => $_POST['meal_name'],
            'calories' => $previousCustomMeal['calories'] + $_POST['calories'],
            'fat' => $previousCustomMeal['fat'] + $_POST['fat'],
            'protein' => $previousCustomMeal['protein'] + $_POST['protein'],
            'carbohydrates' => $previousCustomMeal['carbohydrates'] + $_POST['carbohydrates'],
        ];

        // Convert the array to JSON
        $jsonData = json_encode($customMealData);

        // Set a cookie to store the JSON data
        setcookie('custom_meal', $jsonData, time() + (86400), '/'); // Cookie expires in 1 day
        // Send the JSON data back as the response
        header('Content-Type: application/json');
        echo $jsonData;

        header("Location: profile.php");
    } else {
        echo 'Error: Some data is missing.';
    }
} else {
    echo 'Invalid request method.';
}
?>
