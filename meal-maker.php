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
    // Retrieve the food table with nutriment values
    $query = "SELECT * FROM user WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Assign user data to variables
        $fullName = $user['username'];
        $email = $user['email'];
        $gender = $user['sex'];
        $birthday = $user['birthday'];
        $height = $user['height'];
        $weight = $user['weight'];
        $joinDate = $user['created_at'];
        $plan = $user['plan'];
        if($plan === 1){
            $parsedPlan = "Cut";
        }else if($plan === 2){
            $parsedPlan = "Bulk";
        }
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meal Making</title>
</head>
<body>
    <style>
    
        .navbar {
            background: linear-gradient(to right, black, #222831);
          padding: 10px;
          display: flex;
          align-items: center;
        }
        .right-portion {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .right-portion p{
            text-align:left;
            line-height:40px;
            font-size:22px;
        }

        .right-portion label {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 25px;
        }
        .logo{
          height:128px;
        }
        .navbar button {
            border-radius: 0px;
            background-color: inherit;
            color: #fff;
            padding:fit-content 50px;
            border: none;
            cursor: pointer;
            font-size: 20px;
            float:right;
        }
        .navbar button:hover{
          background-color: #424953;
        }
        .right-portion button {
            border-radius: 25px;
            background-color: #222831;
            color: #fff;
            padding: 20px;
            border: none;
            font-size: 20px;
        }
        .addedfoodfield{
            width: 1300px;            
            border-radius:15px;
        }
        #added-foods {

        background-color:transparent;
        border:none;
        }
        #added-foods button{
            border: solid 2px green;
        }
        .popup {
    position: absolute;
    padding: 20px;
    background-color: #222831;
    color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    width: 250px;
    text-align: center;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease-in-out;
    z-index: 1; /* Ensure the popup is above other elements */
}

.food-button:hover .popup {
    opacity: 1;
    visibility: visible;
}

.popup-content {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 5px;
    text-align: left;
}

.popup-content div {
    line-height: 1.5;
}
.section-content {
        display: grid;
        grid-template-columns: 1fr 1fr; /* Two equal-width columns */
        gap: 30px;
    }
    .food-container {
        height: 300px; /* Set the desired height of the scrollable area */
        overflow-y: auto; /* Add vertical scrollbar when needed */

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
    updateStatusMessage(currentCarbs,currentProtein);
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

    // Update the status message based on the conditions
   
</script>
    <nav class="navbar">
        <a href="home.php" class="logo"><img class="logo" src="images/logo.png"><img>  </a>
        <a href="meal-maker.php">
            <button><i class="fas fa-utensils"></i> Make A Meal</button>
          </a>
          <a href="recipes.php">
            <button><i class="fas fa-book-open"></i> Recipes</button>
          </a>
          <a href="#">
            <button><i class="fas fa-dumbbell"></i> Training</button>
          </a>
          <button><a href="profile.php"><i class="fas fa-user"></i> <?php echo $fullName; ?></a></button>

        <!--{"Contact Us"}-->
  </nav>
    <section id="section1">
        <div class="section-content"style="align-items: start;">
            <div class="left-portion">
                <h1>Add To My Meal</h1>
                <h4>Tip: Select A Diverse Amount of Food</h4>
                <h4>Foods are measured by 100g</h4>
                <div class="scroll food-container">
                    <?php foreach ($foodItems as $food) { ?>
                        <button class="food-button nutriment-add" onclick="incrementNutrition('<?php echo $food['food_name']; ?>', <?php echo $food['calories']; ?>, <?php echo $food['protein']; ?>, <?php echo $food['fat']; ?>, <?php echo $food['carbohydrates']; ?>);">
                            <?php echo $food['food_name']; ?>
                            <div class="popup">
                                <div class="popup-content">
                                    <div><strong>Calories:</strong> <?php echo $food['calories']; ?>Kcal</div>
                                    <div><strong>Protein:</strong> <?php echo $food['protein']; ?>g</div>
                                    <div><strong>Fat:</strong> <?php echo $food['fat']; ?>g</div>
                                    <div><strong>Carbs:</strong> <?php echo $food['carbohydrates']; ?>g</div>
                                </div>
                            </div>
                        </button>
                    <?php } ?>
                </div>
            </div>
            
            <!-- Display the total nutriment values -->
            
            <div class="right-portion">
                
                <section style="height: fit-content">
                <h2>Recommended values for a <?php echo $parsedPlan?> Plan Based on your BMI</h2>
                </section>                
                <button id="etat">Meal Rating(BETA)<span id="etat"></span></button>
                <p>Carbohydrates Intake: <span id="carbin"></span>g</p>
                <button class="nutriment-count" id="nutriment-count-carbs"><?php echo $totalCarbs; ?>g</button>
                <p>Fat Intake: <span id="fatin"></span>g</p>
                <button class="nutriment-count" id="nutriment-count-fat"><?php echo $totalFat; ?>g</button>
                <p>Calories Intake: <span id="calin"></span> kcal</p>
                <button class="nutriment-count" id="nutriment-count-calories"><?php echo $totalCalories; ?>kcal</button>
                <p>Protein Intake: <span id="proin"></span>g</p>
                <button class="nutriment-count" id="nutriment-count-protein"><?php echo $totalProtein; ?>g</button>
                </form>
                
            </div>
            
        </div><form id="meal-form" method="post" action="">
        <input type="hidden" id="totalCarbs" name="totalCarbs" required>
        <input type="hidden" id="totalFat" name="totalFat" required>
        <input type="hidden" id="totalCalories" name="totalCalories" required>
        <input type="hidden" id="totalProtein" name="totalProtein" required>
        <label style="margin-left:30px;" for="meal_name">Meal Name:</label>
        <fieldset class ="addedfoodfield" >
            <legend>
                <input class="mealname"style="background-color: #222831;transparent;padding:12px;border: solid 2px rgb(192, 192, 192);font-size:18px;color:white;" type="text" id="meal_name" name="meal_name" required></legend>
                <div id="added-foods"><!--Foods get added here --></div>
                <button type ="submit" id = "save-meal-button">Save As Custom</button>
            </fieldset>
        </form>
        
    </section>
</body>
        <script>
                //Initial Intakes Setup
            var Weight = <?php echo $weight ?>;
            var Height = <?php echo $height ?>;
            var hm = Height / 100;
            var BMI = Weight / (hm * hm);
            console.log(BMI);

            var proteinIntake;
            var CaloriesIntake;
            var fatIntake;
            var remainingCalories;

            //2 is bulk, 1 is cut
            let plan = <?php echo $plan?>;
            switch (plan) {
                case 1:
                    //Cut
                    proteinIntake = CalculateProteinIntake(Weight, plan);
                    CaloriesIntake = CalculateCalories(Weight, plan);
                    fatIntake = CalculateFats(CaloriesIntake, plan);
                    remainingCalories = CalculateCarbohydrates(CaloriesIntake, proteinIntake, fatIntake);
                    break;
                case 2:
                    //Bulk
                    proteinIntake = CalculateProteinIntake(Weight, plan);
                    CaloriesIntake = CalculateCalories(Weight, plan);
                    fatIntake = CalculateFats(CaloriesIntake, plan);
                    remainingCalories = CalculateCarbohydrates(CaloriesIntake, proteinIntake, fatIntake);
                    break;
            }

            document.getElementById('proin').innerHTML = proteinIntake.toFixed(1);
            document.getElementById('calin').innerHTML = CaloriesIntake.toFixed(1);
            document.getElementById('fatin').innerHTML = fatIntake.toFixed(1);
            document.getElementById('carbin').innerHTML = (remainingCalories / 4).toFixed(1);

            function CalculateProteinIntake(w, plan) {
                if (plan == 1) {
                    return w / 1.5;
                } else if (plan == 2) {
                    return w / 1.7;
                } else {
                    return w / 1.6;
                }
            }

            function CalculateCalories(w, plan) {
                if (plan == 1) {
                    return w * 1.2 + 600;
                } else if (plan == 2) {
                    return w * 1.0 + 400;
                } else {
                    return w * 1.1 + 200;
                }
            }

            function CalculateFats(totalCalories, plan) {
                if (plan == 1) {
                    return 0.2 * totalCalories / 9;
                } else if (plan == 2) {
                    return 0.3 * totalCalories / 9;
                } else {
                    return 0.25 * totalCalories / 9;
                }
            }

            function CalculateCarbohydrates(totalCalories, proteinIntake, fatIntake) {
                let remainingCalories = totalCalories - (proteinIntake * 4 + fatIntake * 9);
                return remainingCalories / 4;
            }
    
        </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get all the buttons with the 'food-button' class
            const buttons = document.querySelectorAll('.food-button');

            // Add event listener to each button
            buttons.forEach(button => {
                button.addEventListener('mouseover', () => {
                    // Get the associated popup using the 'popup' class
                    const popup = button.querySelector('.popup');
                    popup.style.display = 'block';
                });

                button.addEventListener('mouseout', () => {
                    const popup = button.querySelector('.popup');
                    popup.style.display = 'none';
                });
            });
        });
    
        </script>
        <script>
        // Replace this with the action you want to take when the mouse leaves the weight button
        function updateStatusMessage(currentCalories,currentProtein) {
            var etatElement = document.getElementById('etat');
            var etatButton = document.getElementById('etat');
            if (plan == 1) {
                if (currentProtein > parseFloat(document.getElementById('proin').innerHTML) && currentCalories < parseFloat(document.getElementById('carbin').innerHTML)) {
                    statusMessage = 'Perfect!';
                    etatButton.style.backgroundColor = 'green';

                } else {
                    statusMessage = 'Good!';

                }
                if (currentProtein < parseFloat(document.getElementById('proin').innerHTML) && currentCalories > parseFloat(document.getElementById('carbin').innerHTML)) {
                    statusMessage = 'Can Do Some Changes';

                }
            } else if (plan == 2) {
                if (currentProtein > parseFloat(document.getElementById('proin').innerHTML) && currentCalories > parseFloat(document.getElementById('carbin').innerHTML)) {
                    statusMessage = 'Perfect!';
                    etatButton.style.backgroundColor = 'green';
                } else {
                    statusMessage = 'Good!';

                }
                if (currentProtein < parseFloat(document.getElementById('proin').innerHTML) && currentCalories < parseFloat(document.getElementById('carbin').innerHTML)) {
                    statusMessage = 'Can Do Some Changes';

                }
            }

            // Set the status message to the etatElement.textContent
            etatElement.textContent = statusMessage;
        }
        </script>
</html>
