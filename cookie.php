<!DOCTYPE html>
<html>
<head>
    <title>Retrieve Cookie Data</title>
</head>
<body>
    <?php
    if (isset($_COOKIE['custom_meal'])) {
        $cookieData = json_decode($_COOKIE['custom_meal'], true);

        echo '<h2>Custom Meal Data from Cookie:</h2>';
        echo 'Meal Name: ' . $cookieData['meal_name'] . '<br>';
        echo 'Calories: ' . $cookieData['calories'] . '<br>';
        echo 'Fat: ' . $cookieData['fat'] . '<br>';
        echo 'Protein: ' . $cookieData['protein'] . '<br>';
        echo 'Carbohydrates: ' . $cookieData['carbohydrates'] . '<br>';
    } else {
        echo '<p>No custom meal data found in the cookie.</p>';
    }
    ?>
</body>
</html>