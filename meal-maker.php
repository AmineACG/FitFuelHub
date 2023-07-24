<?php
session_start();
// Check if the user is not logged in and redirect to the login page if necessary


// Retrieve form data
$dsn = 'mysql:host=localhost;dbname=fitfuelhub_db';
$username = 'root';
$password = '';

try {
    // Connect to the database
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve the food table with nutriment values
    $query = "SELECT * from food_item";
    $stmt = $db->query($query);
    $foodItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalCarbs = 0;
    $totalFat = 0;
    $totalCalories = 0;
    $totalProtein = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $mealName = $_POST['meal_name'];
    $calories = $_POST['totalCalories'];
    $protein = $_POST['totalProtein'];
    $carbohydrates = $_POST['totalCarbs'];
    $fat = $_POST['totalFat'];

    // Validate form data (you can add more validation if needed)
    if (empty($mealName) || empty($calories) || empty($protein) || empty($carbohydrates) || empty($fat)) {
        $error = "All fields are required.";
    } else {
        // Insert custom meal data into the database
        $query = "INSERT INTO custom_meal (meal_name, user_id, calories, protein, carbohydrates, fat, created_at) VALUES (:meal_name, :user_id, :calories, :protein, :carbohydrates, :fat, NOW())";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':meal_name' => $mealName,
            ':user_id' => $_SESSION['user_id'],
            ':calories' => $calories,
            ':protein' => $protein,
            ':carbohydrates' => $carbohydrates,
            ':fat' => $fat
        ]);
    }
}

} catch (PDOException $e) {
    // Handle any database errors
    echo 'Database Error: ' . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meal Making</title>
</head>
<body>
    <style>
        .right-portion {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .right-portion label {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 25px;
        }

        .right-portion button {
            border-radius: 25px;
            background-color: #222831;
            color: #fff;
            padding: 20px;
            border: none;
            font-size: 20px;
        }
        #added-foods {
            background-color:#393E46;
            margin-bottom: 20px;
            padding: 10px;
            height: 100%;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            height:120px;
            width: 95%;
            border: 3px solid #242b36;

            
            }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    // Get the form and buttons
    const mealForm = document.getElementById('meal-form');
    const saveMealButton = document.getElementById('save-meal-button');

    // Add event listener to the "Save Meal" button
    saveMealButton.addEventListener('click', function (event) {
        event.preventDefault(); // Prevent form submission

        // Get the total nutriment values from hidden input fields
        const totalCarbs = parseFloat(document.getElementById('totalCarbs').value);
        const totalFat = parseFloat(document.getElementById('totalFat').value);
        const totalCalories = parseFloat(document.getElementById('totalCalories').value);
        const totalProtein = parseFloat(document.getElementById('totalProtein').value);

        // Set the values of hidden input fields in the form (Optional, but useful if the user manually changes the values in the form before submission)
        document.getElementById('totalCarbs').value = totalCarbs;
        document.getElementById('totalFat').value = totalFat;
        document.getElementById('totalCalories').value = totalCalories;
        document.getElementById('totalProtein').value = totalProtein;

        // Submit the form
        if (mealForm) {
            mealForm.submit();
        }
    });
});


    function incrementNutrition(foodName, calories, protein, fat, carbs) {
    var caloriesElement = document.getElementById("nutriment-count-calories");
    var proteinElement = document.getElementById("nutriment-count-protein");
    var fatElement = document.getElementById("nutriment-count-fat");
    var carbsElement = document.getElementById("nutriment-count-carbs");

    var currentCalories = parseInt(caloriesElement.innerHTML);
    var currentProtein = parseInt(proteinElement.innerHTML);
    var currentFat = parseInt(fatElement.innerHTML);
    var currentCarbs = parseInt(carbsElement.innerHTML);

    // Increment the total nutriment values
    currentCalories += calories;
    currentProtein += protein;
    currentFat += fat;
    currentCarbs += carbs;

    // Update the buttons with the new nutrient values
    caloriesElement.innerHTML = currentCalories + ' kcal';
    proteinElement.innerHTML = currentProtein + 'g';
    fatElement.innerHTML = currentFat + 'g';
    carbsElement.innerHTML = currentCarbs + 'g';

    // Update the hidden input fields with the new nutrient values
    document.getElementById('totalCalories').value = currentCalories;
    document.getElementById('totalProtein').value = currentProtein;
    document.getElementById('totalFat').value = currentFat;
    document.getElementById('totalCarbs').value = currentCarbs;

    var addedFoodsElement = document.getElementById("added-foods");
    var newButton = document.createElement("button");
    newButton.innerText = foodName;
    addedFoodsElement.appendChild(newButton);
}


    </script>
    <nav class="navbar">
    <a href="home.php" class="logo">
            <img class="logo" src="images/FitHub.png"><img>  
        </a>
        <button href="#">Diet</button>
        <button href="#">Training</button>
        <a href="logout.php"><button>Log Out</button></a>
    </nav>
    <section id="section1">
        <div class="section-content"style="align-items: start;">
            <div class="left-portion">
                <h1>Add To My Meal</h1>
                <h4>Foods are measured by 100g</h1>
                <?php foreach ($foodItems as $food) { ?>
                    <button class="nutriment-add" onclick="incrementNutrition('<?php echo $food['food_name']; ?>', <?php echo $food['calories']; ?>, <?php echo $food['protein']; ?>, <?php echo $food['fat']; ?>, <?php echo $food['carbohydrates']; ?>);">
                        <?php echo $food['food_name']; ?>
                    </button>
                <?php } ?>
            </div>
            
            <!-- Display the total nutriment values -->
            <div class="right-portion">
                <section style="height: fit-content">
                    <h1>Perfect!</h1>
                </section><br>
                <label>Carbs</label>
                <button class="nutriment-count" id="nutriment-count-carbs"><?php echo $totalCarbs; ?>g</button>
                <label>Fat</label>
                <button class="nutriment-count" id="nutriment-count-fat"><?php echo $totalFat; ?>g</button>
                <label>Calories</label>
                <button class="nutriment-count" id="nutriment-count-calories"><?php echo $totalCalories; ?>kcal</button>
                <label>Protein</label>
                <button class="nutriment-count" id="nutriment-count-protein"><?php echo $totalProtein; ?>g</button>
                
                </form>
            </div>
            
        </div>
        <div id="added-foods"><!--Foods get added here --></div>
        <form id="meal-form" method="post" action="">
        <input type="hidden" id="totalCarbs" name="totalCarbs" required>
        <input type="hidden" id="totalFat" name="totalFat" required>
        <input type="hidden" id="totalCalories" name="totalCalories" required>
        <input type="hidden" id="totalProtein" name="totalProtein" required>
            <label for="meal_name">Meal Name:</label>
            <input type="text" id="meal_name" name="meal_name" required>   
            <button type ="submit" id = "save-meal-button">Save As Custom</button>
        </form>
    </section>
</body>
</html>
