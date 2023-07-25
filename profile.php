<?php
session_start();
// Check if the user is not logged in and redirect to the login page if necessary

$dsn = 'mysql:host=localhost;dbname=fitfuelhub_db';
$username = 'root';
$password = '';

try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM recipes";
    $stmt = $db->query($query);
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query = "SELECT * FROM custom_meal";
    $stmt = $db->query($query);
    $customMeals = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
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
    <title>Recipe Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 1rem 0;
        }

        .section-content {
        display: flex;
    }

    .left-portion {
        flex: 1;
        max-width: 70%;
        height: 100%;
        background-color: #fff;
        border-radius: 5px;
        padding: 1rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .right-portion {
        flex: 1;
        max-width: 30%; /* Adjust the max-width to your preference */
        height: 100%;
        background-color: #fff;
        border-radius: 5px;
        padding: 1rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
        .container {
            max-width: 960px;
            margin: 0 auto;
        }

        .recipe {
            display: flex;
            border-bottom: 1px solid #ccc;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .recipe:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .gray-container {
          overflow:hidden;
          background-color: #aaa;
          width: 50%;
          max-height:375px;
          margin-right: 25px;
          
        }
        .gray-container img{
          height: 100%;
          width: 100%;
          object-fit: cover;

        }

        .recipe-details {
            flex: 1;
            text-align:left;
        }

        .recipe h2 {
            margin: 0;
            margin-bottom: 0.5rem;
            font-size: 24px;
        }

        .recipe p {
            margin: 0;
            margin-bottom: 0.5rem;
        }

        .recipe button {
            background-color: #222;
            color: #fff;
            display:flex;
            border: none;
            flex:1;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width:40%;
            transition: background-color 0.2s;
        }
        .navbar {
            background-color: #222831;
            padding: 10px;
            display: flex;
            align-items: center;
            }

        .recipe button:hover {
            background-color: #444;
        }
        .custom{
            display:flex;
        }
        .custom-details{
           width:95%;
        }
    </style>
</head>
<body>
<nav class="navbar">
        <a href="#" class="logo"><img class="logo" src="images/FitHub.png"><img>  </a>
        <a href="meal-maker.php"><button>Make A Meal</button></a>
        <a href="recipes.php"><button>Recipes</button></a>
        <a href="#" ><button>Training</button></a>
        <!--{"Contact Us"}-->      
  </nav>
  <section class="section-content">
    <div class="right-portion">
        <h1>My Custom Meals:</h1>
        <?php if (empty($customMeals)) { ?>
            <!-- Display a button to add a custom meal when there are no custom meals -->
            <h2>No custom meals found. <a href="meal-maker.php">Click here to add one</a>.</h2>
        <?php } else { ?>
            <!-- Display custom meals if available -->
            <?php foreach ($customMeals as $customMeal) { ?>
                <article class="custom">
                    <div class="custom-details">
                        <h2><?php echo $customMeal['meal_name']; ?></h2>
                        <p><strong>Calories:</strong> <?php echo $customMeal['calories']; ?></p>
                        <p><strong>Fat:</strong> <?php echo $customMeal['fat']; ?>g</p>
                        <p><strong>Protein:</strong> <?php echo $customMeal['protein']; ?>g</p>
                        <p><strong>Carbohydrates:</strong> <?php echo $customMeal['carbohydrates']; ?>g</p>
                        <!-- Add other custom meal details here as needed -->

                        <!-- Optionally, you can add a form to handle actions like editing or deleting the custom meal -->
                        <form method="post" action="custom_meal.php">
                            <input type="hidden" name="meal_id" value="<?php echo $customMeal['id']; ?>">
                            <button type="submit">Edit</button>
                            <input type="hidden" name="meal_id" value="<?php echo $customMeal['id']; ?>">
                            <button type="submit">Edit</button>
                        </form>
                    </div>
                </article>
            <?php } ?>
        <?php } ?>
    </div>
</section>

<section class ="section-content">
        <div class="left-portion">
            <div class="container">
                <?php foreach ($recipes as $recipe) { ?>
                    <article class="recipe">
                        <div class ="gray-container">
                        <img src="<?php echo $recipe['image']; ?>" alt="<?php echo $recipe['recipe_name']; ?>">
                </div>
                        <div class="recipe-details">
                            <h2><?php echo $recipe['recipe_name']; ?></h2>
                            <p><strong>Description:</strong> <?php echo $recipe['description']; ?></p>
                            <p><strong>Preparation Time:</strong> <?php echo $recipe['preparation_time']; ?> minutes</p>
                            <p><strong>Cooking Time:</strong> <?php echo $recipe['cooking_time']; ?> minutes</p>
                            <p><strong>Servings:</strong> <?php echo $recipe['servings']; ?></p>
                            <p><strong>Calories:</strong> <?php echo $recipe['calories']; ?></p>
                            <p><strong>Fat:</strong> <?php echo $recipe['fat']; ?>g</p>
                            <p><strong>Protein:</strong> <?php echo $recipe['protein']; ?>g</p>
                            <p><strong>Carbohydrates:</strong> <?php echo $recipe['carbohydrates']; ?>g</p>
                                <button type="submit">Add to my meals</button>
                        </div>
                    </article>
                <?php } ?>
            </div>
        </div>
</section>

</body>
</html>
