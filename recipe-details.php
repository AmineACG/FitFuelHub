<?php
session_start();

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
if (isset($_SESSION['user_id'])) {
    // Retrieve the user's username from the database
    $query = "SELECT username FROM user WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $loggedInUser = $user['username'];
}
// Check if the required data is present in the URL query parameters
$label = $_GET['label'];
$source = $_GET['source'];
$preparationTime = $_GET['preparation_time'];
$calories = $_GET['calories'];
$fat = $_GET['fat'];
$protein = $_GET['protein'];
$carbohydrates = $_GET['carbohydrates'];
$image = $_GET['image'];
$servings = $_GET['servings'];
$totalWeight = $_GET['totalWeight'];

// Set default empty arrays for dietLabels, healthLabels, and cautions
$dietLabels = isset($_GET['dietLabels']) ? $_GET['dietLabels'] : [];
$healthLabels = isset($_GET['healthLabels']) ? $_GET['healthLabels'] : [];
$cautions = isset($_GET['cautions']) ? $_GET['cautions'] : [];
$ingredientLines = explode(',', $_GET['ingredientLines']);
?>

<!-- Add your desired HTML structure and styling for the recipe details page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $label; ?> - Recipe Details</title>
    <!-- Add your CSS styles here (e.g., style.css) -->
</head>
<body>
<nav class="navbar">
        <a href="home.php" class="logo"><img class="logo" src="images/logo.png"><img>  </a>
        
        <!--{"Contact Us"}-->
        <?php if (isset($_SESSION['user_id'])) { ?>
          <a href="meal-maker.php">
            <button><i class="fas fa-utensils"></i> Make A Meal</button>
          </a>
          <a href="recipes.php">
            <button><i class="fas fa-book-open"></i> Recipes</button>
          </a>
          <a href="training.php">
            <button style ="color:red;"><i class="fas fa-dumbbell"></i> Training(Coming Soon)</button>
          </a>
          <button style="margin-left:240px;"><a href="profile.php"><i class="fas fa-user"></i> <?php echo $loggedInUser; ?></a></button>
        <?php } else { ?>
          <button onclick="showPopup()" style="color:inherit;background-color:white;">
            <i class="fas fa-sign-in-alt"></i> <a style="">Log In</a>
          </button>
          <a style="" href="SignUp.php">
            <button><i class="fas fa-user-plus"></i> Sign Up</button>
          </a>
        <?php } ?>
  </nav>
<style>
    button {
            border-radius: 50px;
            background-color: black;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 18px;
            margin-top: 10px;
        }

        .button:hover {
            background-color: #424953;
            border:1px solid white;

        }
    .navbar {
          background: linear-gradient(to right, black, #222831);
          padding: 10px;
          display: flex;
          align-items: center;
        }
        a {
            text-decoration: none;
            color: inherit;
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
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
        }
        .recipe-container {
            display: flex;
            max-width: 85%;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }
        .gray-container {
            overflow:hidden;
            background-color: white;
            max-width:40%;
            max-height:300px;
            margin-right: 25px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.5);

            }
            .gray-container img{
            height: 100%;
            width: 100%;
            object-fit:cover;
            }

        .recipe-details {
            flex: 1;
            margin-left: 20px;
        }
        h1 {
            margin: 0;
        }
        h2 {
            margin-top: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        ul li {
            margin-bottom: 5px;
        }
        .health-labels {
            margin-top: 20px;
        }
        .health-labels button {
            border:2px solid black;
            background-color:white;
           color: black;
            padding: 8px 12px;
            margin-right: 5px;
            margin-bottom: 5px;
            border-radius: 5px;
            cursor: pointer;
            transition: padding 0.3s ease-out;
        }
        .health-labels button:hover {
            border:2px solid darkred;
           color: darkred;
           padding: 12px 14px;

        }
        p {
            margin: 0;
            line-height: 1.6;
            font-size:20px;
        }
        strong {
            font-weight: bold;
        }
    </style>
    <header>
        <h1><?php echo $label; ?></h1>
    </header>
    <main>
        <div class="recipe-container">
            <div class="gray-container">
                
                <img src="<?php echo $image; ?>" alt="<?php echo $label; ?>">
            </div>
            <div class="recipe-details">
            <h2>Meal Informations:</h2>
                <p><strong>Source:</strong> <?php echo $source; ?></p>
                <p><strong>Preparation Time:</strong> <?php echo $preparationTime; ?> minutes</p>
                <p><strong>Calories:</strong> <?php echo $calories; ?></p>
                <p><strong>Fat:</strong> <?php echo $fat; ?>g</p>
                <p><strong>Protein:</strong> <?php echo $protein; ?>g</p>
                <p><strong>Carbohydrates:</strong> <?php echo $carbohydrates; ?>g</p>
                <p><strong>Servings:</strong> <?php echo $servings; ?></p>
                <p><strong>Total Weight:</strong> <?php echo $totalWeight; ?> grams</p>
                <h2>Ingredients:</h2>
                <ul>
                <?php
                foreach ($ingredientLines as $ingredient) {
                    echo "<button style='cursor:default;border:2px solid black; margin-right:10px;background-color:white;color:black'>$ingredient</button>";
                }
                ?>
                </ul>
                <h2>Diet Labels:</h2>
                <ul>
                <?php
                foreach ($dietLabels as $label) {
                    echo "<button style='color:darkred;background-color:white;border:2px solid darkred;'>$label</button>";
                }
                ?>
                </ul>
                <div class="health-labels">
                    <h2>Health Labels:</h2>
                    <?php
                    foreach ($healthLabels as $label) {
                        echo "<button style='cursor:default;'>$label</button>";
                    }
                    ?>
                </div>    
                <div  style="text-align: center;">
        <button type="button" onclick="addToMyMeals('<?php echo $label; ?>', <?php echo $calories; ?>, <?php echo $fat; ?>, <?php echo $protein; ?>, <?php echo $carbohydrates; ?>)">
            <strong style="font-size: 28px;">Add to my meals
        </button>
    </div>           
                </div>
            </div>
        </div>
    </main>
    <script> 
    // Function to add a custom meal to the database
    function addToMyMeals(mealName, calories, fat, protein, carbohydrates) {
        const formData = new FormData();
        formData.append('meal_name', mealName);
        formData.append('totalCalories', calories);
        formData.append('totalFat', fat);
        formData.append('totalProtein', protein);
        formData.append('totalCarbs', carbohydrates);

        // Make a POST request to the server to add the custom meal
        fetch('meal-maker.php', {
            method: 'POST',
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                // Display a success message or update the custom meals list
                console.log('Custom meal added successfully:', data);
                alert('Custom meal added successfully.');
            })
            .catch((error) => {
                console.error('Error adding custom meal:', error);
            });
    }

    // ... (Your existing code)
</script>
</body>
</html>