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

    $user_id = $_SESSION['user_id']; // Replace 'user_id' with the actual session variable holding the user ID

    $query = "SELECT * FROM custom_meal WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $customMeals =
    $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
            flex:1;
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
            height: 100%;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
            .container {
                max-width: 960px;
                margin: 0 auto;
            }
            .delete:hover{
                background-color:darkred;
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
            background-color: white;
            width: 50%;
            max-height:375px;
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
                display: flex;
                border: none;
                flex: 1;
                padding: 8px 16px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                width: 40%;
                transition: background-color 0.2s, width 0.3s; /* Add transition properties for background-color and width */
            }
            .navbar {
                background: linear-gradient(to right, black, #222831);
                padding: 10px;
                display: flex;
                align-items: center;
                }

                .recipe button:hover {
                background-color: darkgreen;
                width: 45%; /* Increase the width slightly on hover for a nice effect */
            }
            .custom{
            flex: 1 0 25%; /* Each custom meal takes 25% of the container width (4 custom meals per row) */
            margin: 0 0.5rem;
            }
                    .custom-row {
                display: flex;
                flex-wrap: wrap;
                margin-bottom: 1rem;
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
            .custom-details {
                /* Your existing styles for individual custom meals */
                /* Adjust width, margin, or padding if needed */
                flex: 1 0 25%; /* Each custom meal takes 25% of the container width (4 custom meals per row) */
                margin: 0 0.5rem;
            }
            .logo{
            height:128px;
            }
            #searchInput {
                width: 100%;
                max-width: 400px;
                padding: 10px;
                font-size: 16px;
                border: 2px solid #ccc;
                border-radius: 5px;
                outline: none;
                transition: border-color 0.3s;
                }

                #searchInput:focus {
                border-color: darkred;
                box-shadow: 0 0 5px rgba(85, 85, 85, 0.5);
            }
            #searchButton {
            padding: 15px 20px;
            font-size: 16px;
            background-color: #222;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            }

            #searchButton:hover {
            background-color: #444;
            }   
            .custom-container {
                display: flex;
                flex-wrap: wrap;
                margin-bottom: 1rem;
            }
            
           
    </style>
    <script>
        const changeButton = document.getElementById("changeButton");
        const contentDiv = document.getElementById("content");

        changeButton.addEventListener("click", function() {
            // Change the content
            contentDiv.innerHTML = "<p>Meal Added Successfully</p>";

            // Add the 'success' class to change the background color to green
            contentDiv.classList.add("success");
        });
        </script>
</head>
<body>
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
          <input type="text" id="searchInput" placeholder="Search recipes...">
        <button id="searchButton">Search</button>

        <!--{"Contact Us"}-->      
  </nav>
  <section class="section-content">
    <div class="right-portion">
        <h1>Today's Meals:</h1>
        <?php if (empty($customMeals)) { ?>
            <!-- Display a button to add a custom meal when there are no custom meals -->
            <h2>No custom meals found. <a href="meal-maker.php"><i>Click here to add one</i></a>.</h2>
        <?php } else { ?>
            <!-- Display custom meals if available -->
            <article class="custom">
            <div class="custom-container">
            <?php
        // Split the custom meals into chunks, with each chunk containing a maximum of 4 custom meals
        $customMealChunks = array_chunk($customMeals, 5);

        // Loop through the chunks and create rows for custom meals
        foreach ($customMealChunks as $customMealChunk) {
            echo '<article class="custom-row">';
            
            foreach ($customMealChunk as $customMeal) {
                echo '<div class="custom-details">';
                echo '<h2>' . $customMeal['meal_name'] . '</h2>';
                echo '<p><strong>Calories:</strong> ' . $customMeal['calories'] . '</p>';
                echo '<p><strong>Fat:</strong> ' . $customMeal['fat'] . 'g</p>';
                echo '<p><strong>Protein:</strong> ' . $customMeal['protein'] . 'g</p>';
                echo '<p><strong>Carbohydrates:</strong> ' . $customMeal['carbohydrates'] . 'g</p>';
                // Add other custom meal details here as needed

                // Optionally, you can add a form to handle actions like editing or deleting the custom meal
                echo '<form method="post" action="delete-custom.php">';
                echo '<input type="hidden" name="id" value="' . $customMeal['id'] . '">';
                echo '<button type="submit">Edit</button>';
                echo '<input type="hidden" name="meal_id" value="' . $customMeal['id'] . '">';
                echo '<button class="delete" type="submit">Delete</button>';
                echo '</form>';

                echo '</div>';
            }

            echo '</article>';
        }
        ?>
            </article>
            </div>
        <?php } ?>
    </div>
</section>

<section class ="section-content">
        <div class="left-portion">
            <div class="container">
                <?php foreach ($recipes as $recipe) { ?>
                    <article class="recipe">
                        <div class ="gray-container">
                        <img src="images/recipes/<?php echo $recipe['image']; ?>" alt="<?php echo $recipe['recipe_name']; ?>">
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
                            <button type="submit"><strong style="font-size:18px;">+</strong>Add to my meals </button>
                        </div>
                    </article>
                <?php } ?>
                <h1>Search For More Recipes</h1>
                <p style="font-size:22px;">Make Sure To Spell The Ingredients Correctly !</p>
                <p style="font-size:16px;">Examples: Eggs,Chicken,Pasta etc..</p>

            </div>
        </div>
</section>
<script>
    // Function to fetch recipes from the Edamam API and display them
    function fetchRecipesFromAPI(searchQuery) {
        const apiUrl = `fetch_recipes.php?q=${encodeURIComponent(searchQuery)}`;

        // Make a fetch API request to the custom endpoint (fetch_recipes.php)
        fetch(apiUrl)
            .then((response) => response.json())
            .then((data) => {
                const container = document.querySelector('.left-portion .container');
                container.innerHTML = '';

                // Loop through the retrieved recipes and create HTML elements to display them
                data.hits.forEach((hit) => {
                    const recipe = hit.recipe;
                    const recipeHTML = `
                        <article class="recipe">
                            <div class="gray-container">
                                <img src="${recipe.image}" alt="${recipe.label}">
                            </div>
                            <div class="recipe-details">
                                <h2>${recipe.label}</h2>
                                <p><strong>Description:</strong> ${recipe.source}</p>
                                <p><strong>Preparation Time:</strong> ${recipe.totalTime} minutes</p>
                                <p><strong>Calories:</strong> ${recipe.calories.toFixed(2)}</p>
                                <p><strong>Fat:</strong> ${recipe.totalNutrients.FAT.quantity.toFixed(2)}g</p>
                                <p><strong>Protein:</strong> ${recipe.totalNutrients.PROCNT.quantity.toFixed(2)}g</p>
                                <p><strong>Carbohydrates:</strong> ${recipe.totalNutrients.CHOCDF.quantity.toFixed(2)}g</p>
                                <button type="button" onclick="addToMyMeals('${recipe.label}', ${recipe.calories}, ${recipe.totalNutrients.FAT.quantity.toFixed(2)}, ${recipe.totalNutrients.PROCNT.quantity.toFixed(2)}, ${recipe.totalNutrients.CHOCDF.quantity.toFixed(2)})">
                                    <strong style="font-size: 18px;">+</strong>Add to my meals</button>
                                
                            </div>
                        </article>
                    `;

                    container.innerHTML += recipeHTML;
                });
            })
            .catch((error) => {
                console.error('Error fetching recipes:', error);
            });
    }

    // Function to add a custom meal to the database
    function addToMyMeals(mealName, calories, fat, protein, carbohydrates) {
        const formData = new FormData();
        formData.append('meal_name', mealName);
        formData.append('totalCalories', calories);
        formData.append('totalFat', fat);
        formData.append('totalProtein', protein);
        formData.append('totalCarbs', carbohydrates);

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

    // Event listener to handle the search form submission
    document.getElementById('searchForm').addEventListener('submit', (event) => {
        event.preventDefault();
        const searchQuery = document.getElementById('searchInput').value;
        fetchRecipesFromAPI(searchQuery);
    });

    // Display the initial recipes (using the default search query)
    fetchRecipesFromAPI('chicken');
</script>
<script>
    // ... (Existing JavaScript code)

    // Function to handle the search button click event
    function handleSearch() {
        const searchQuery = $('#searchInput').val().trim(); // Get the user input from the search bar
        if (searchQuery !== '') {
            // Clear the existing recipes before fetching new ones based on the search query
            $('.container').empty();

            // Fetch recipes based on the search query
            fetchRecipesFromAPI(searchQuery);
        }
    }

    // Attach the handleSearch function to the click event of the search button
    $('#searchButton').click(function () {
        handleSearch();
    });

    // Alternatively, you can trigger the search when the user presses the Enter key in the search bar
    $('#searchInput').keypress(function (event) {
        if (event.keyCode === 13) { // 13 is the key code for Enter
            handleSearch();
        }
    });
    function attachAddToMyMealsEvent() {
        const addToMyMealsButtons = document.querySelectorAll('.recipe button');
        addToMyMealsButtons.forEach((button) => {
            button.addEventListener('click', function () {
                // Change the text content of the button to "Meal Added Successfully"
                this.textContent = "Meal Added Successfully";

                // Add the 'success' class to change the background color to green
                this.classList.add('success');
            });
        });
    }
    document.addEventListener('DOMContentLoaded', attachAddToMyMealsEvent);
</script>
</body>
</html>
