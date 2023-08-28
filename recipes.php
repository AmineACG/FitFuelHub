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
    $customMeals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query = "SELECT * FROM favorite_meal WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $favoriteMeals = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

       

                .left-right-container {
            /* Add styles for the wrapper container */
            display: flex;
            flex-wrap: wrap; /* Allow the children to wrap to a new line if needed */
            gap: 10px; /* Add some gap between left-portion and right-side */
        }

        .left-portion {
            /* Your existing styles for the left-portion */
            flex: 1;
            width: 80%;
            /* Adjust the width as needed */
        }

        .right-side {
            /* Styles for the new section on the right side */
            width: 400px;
            height:fit-content;
            /* Adjust the width as needed */
            background-color:white;
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
                display: grid;
                margin-bottom: 15px;
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

                box-shadow: 0px 5px 15px black;
                padding:10px 20px;
                width:200px;
                transition: box-shadow 0.5s;
                /* Adjust width, margin, or padding if needed */
            }
            .custom-details:hover{
                
                box-shadow: 0px 5px 30px black;
            }
            .logo{
            height:128px;
            }
            #searchInput {
                width: 90%;
                max-width: 400px;
                padding: 15px;
                font-size: 16px;
                border: 2px solid #ccc;
                border-radius: 0px;
                outline: none;
                border-right:none;
                transition: border-color 0.3s;
                }

                #searchInput:focus {
                border-color: darkred;
                box-shadow: 0 0 5px rgba(85, 85, 85, 0.5);
            }
            #searchButton {
                margin:0px;
            padding: 14px 20px;
            font-size: 16px;
            background-color: #222;
            color: #fff;
            border: 2px solid white;
            border-left:none;
            border-top-right-radius: 50%;
            border-bottom-right-radius: 50%;
            cursor: pointer;
            transition: background-color 0.3s;
            }

            #searchButton:hover {
            background-color: #444;
            }   
            .custom-container {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 10px; /* Set a smaller gap value, adjust as needed */
                margin-bottom: 1rem;
            }
            .header{
                background-color:#222;
                color:white;
                padding-top:10px;
                padding-bottom:10px;

            }
            .favorite:hover{
                background-color:#d4b800;
            }
            .section-content {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: stretch;
            width: 98%;
            height: 90%;
            margin: 10px;
            }
           .favorite-details{
            width:100%;
            padding:0px;            
            box-shadow: 0px 2px 3px black;
            margin-bottom:15px;
        }
        .favorite-main{
            padding:10px 20px;
            
        }
           .favorite-details .delete{
            width:197px;
            border-radius:50px;
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
          <input type="text" id="searchInput" placeholder="Search for ingredients e.g oat,almonds...">
        <button id="searchButton"><i class="fas fa-search"></i></button>
        <button><a href="profile.php"><i class="fas fa-user"></i></a></button>

        <!--{"Contact Us"}-->      
  </nav>
  <section class="section-content">
    <div class="right-portion">
        <div class="header">
            <h1>Today's Meals:</h1>
        </div>
        <div>
        <?php if (empty($customMeals)) { ?>
            <!-- Display a button to add a custom meal when there are no custom meals -->
            <h2>No custom meals found. <a style="text-decoration:underline;" href="meal-maker.php"><i>Click here to add one</i></a>.</h2>
            <h3>- Or Search for a meal and add it !</h3>    
            <?php } else { ?>
            <!-- Display custom meals if available -->
            <div class="custom-container">
                <?php
                foreach ($customMeals as $customMeal) {
                    echo '<div class="custom-details">';
                    echo '<form method="post" action="favorite.php">';
                if($customMeal['favorite'] === 0){
                    echo '<button class ="favorite" type="submit" style="width: 60px; margin:10px 20px;border-radius:50px;" ><i class="far fa-star"></i></button>';
                }else{
                    echo '<button style="background-color:#d4b800;" disabled><i class="far fa-star"></i></button>';
                }
                    echo '<h2>' . $customMeal['meal_name'] .'</h2>';
                    echo '<p><strong>Calories:</strong> ' . $customMeal['calories'] . ' Kcal</p>';
                    echo '<p><strong>Fat:</strong> ' . $customMeal['fat'] . 'g</p>';
                    echo '<p><strong>Protein:</strong> ' . $customMeal['protein'] . 'g</p>';
                    echo '<p><strong>Carbohydrates:</strong> ' . $customMeal['carbohydrates'] . 'g</p>';
                    echo '<input type="hidden" name="meal_name" value="' . $customMeal['meal_name'] . '">';
                    echo '<input type="hidden" name="calories" value="' . $customMeal['calories'] . '">';
                    echo '<input type="hidden" name="fat" value="' . $customMeal['fat'] . '">';
                    echo '<input type="hidden" name="protein" value="' . $customMeal['protein'] . '">';
                    echo '<input type="hidden" name="carbohydrates" value="' . $customMeal['carbohydrates'] . '">';
                    echo '</form>';
                    // Add other custom meal details here as needed
                    
                    // Optionally, you can add a form to handle actions like editing or deleting the custom meal
                    echo '<form method="post" action="finish-meal.php">';
                    echo '<input type="hidden" name="meal_name" value="' . $customMeal['meal_name'] . '">';
                    echo '<input type="hidden" name="calories" value="' . $customMeal['calories'] . '">';
                    echo '<input type="hidden" name="fat" value="' . $customMeal['fat'] . '">';
                    echo '<input type="hidden" name="protein" value="' . $customMeal['protein'] . '">';
                    echo '<input type="hidden" name="carbohydrates" value="' . $customMeal['carbohydrates'] . '">';
                    echo '<button type="submit"><i class="fas fa-check"></i> Finished</button>';
                    echo '</form>';

                    // Second form for the "Delete" action
                    echo '<form method="post" action="delete-custom.php">';
                    echo '<input type="hidden" name="id" value="' . $customMeal['id'] . '">';
                    echo '<button class="delete" type="submit"><i class="fas fa-times"></i> Delete</button>';
                    echo '</form>';

                    echo '</div>';
                }
                ?>
            </div>
        <?php } ?>
            </div>
    </div>
</section>


<section class ="section-content">
    <div class="left-right-container">
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
                <h1>-Search For More Recipes-</h1>
                <p style="font-size:22px;">Make Sure To Spell The Ingredients Correctly !</p>
                <p style="font-size:16px;">Examples: Eggs,Chicken,Pasta etc..</p>

            </div>
        </div>
        <section class="right-side">
        <h1>Favorites:</h1>
            <!-- Your content for the new section -->
            <?php
                if(empty($favoriteMeals)){
                    echo'<h1>What About Favorising Some Meals?</h1>';
                }else{
                    foreach ($favoriteMeals as $favoriteMeal) {
                        echo '<div class="favorite-details">';
                        echo '<h2 style="color:white; background-color:black;margin:0px;height:50px;text-align:center;padding-top:15px;">' . $favoriteMeal['meal_name'] . '</h2>';
                            echo '<div class ="favorite-main">';
                                                echo '<div style="text-align:right;"><i class="far fa-star"></i></div>';
                                echo '<p><strong>Calories:</strong> ' . $favoriteMeal['calories'] . '</p>';
                                echo '<p><strong>Fat:</strong> ' . $favoriteMeal['fat'] . 'g</p>';
                                echo '<p><strong>Protein:</strong> ' . $favoriteMeal['protein'] . 'g</p>';
                                echo '<p><strong>Carbohydrates:</strong> ' . $favoriteMeal['carbohydrates'] . 'g</p>';
                        
                                echo '<input type="hidden" name="meal_name" value="' . $favoriteMeal['meal_name'] . '">';
                                echo '<input type="hidden" name="calories" value="' . $favoriteMeal['calories'] . '">';
                                echo '<input type="hidden" name="fat" value="' . $favoriteMeal['fat'] . '">';
                                echo '<input type="hidden" name="protein" value="' . $favoriteMeal['protein'] . '">';
                                echo '<input type="hidden" name="carbohydrates" value="' . $favoriteMeal['carbohydrates'] . '">';
                                echo '<button onclick="addToMyMeals(\'' . $favoriteMeal['meal_name'] . '\', ' . $favoriteMeal['calories'] . ', ' . $favoriteMeal['fat'] . ', ' . $favoriteMeal['protein'] . ', ' . $favoriteMeal['carbohydrates'] .',' . $favoriteMeal['favorite']  .')"><i class="fas fa-plus"></i> Add To My Meals</button>';
                            

                                // Second form for the "Delete" action
                                echo '<form method="post" action="delete-favorite.php">';
                                echo '<input type="hidden" name="id" value="' . $favoriteMeal['id'] . '">';
                                echo '<button class="delete" type="submit"><i class="fas fa-times"></i> Delete</button>';
                                echo '</form>'; 
                            echo '</div>'; 
                        echo'</div>'; 
                        }
                }
            ?>
            
        </section>
    </div>            
</section>
<style>
  .recipe-details ul {
    display: none;
  }
    </style>
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
    <a href="recipe-details.php?${getRecipeDetailsQueryParams(recipe)}">
            <img src="${recipe.image}" alt="${recipe.label}">
            </a>    
        </div>
    <div class="recipe-details">
        <h2>${recipe.label}</h2>
        <p><strong>Source:</strong> ${recipe.source}</p>
        <p><strong>Preparation Time:</strong> ${recipe.totalTime} minutes</p>
        <p><strong>Calories:</strong> ${recipe.calories.toFixed(2)}</p>
        <p><strong>Fat:</strong> ${recipe.totalNutrients.FAT.quantity.toFixed(2)}g</p>
        <p><strong>Protein:</strong> ${recipe.totalNutrients.PROCNT.quantity.toFixed(2)}g</p>
        <p><strong>Carbohydrates:</strong> ${recipe.totalNutrients.CHOCDF.quantity.toFixed(2)}g</p>
        <p><strong>Servings:</strong> ${recipe.servings}</p>
        <p><strong>Total Weight:</strong> ${recipe.totalWeight.toFixed(2)}g</p>
        
        <ul>
            ${recipe.ingredientLines.map(ingredient => `<li>${ingredient}</li>`).join('')}
        </ul>
        <ul>
            ${recipe.dietLabels.map(label => `<li>${label}</li>`).join('')}
        </ul>
        <ul>
            ${recipe.healthLabels.map(label => `<li>${label}</li>`).join('')}
        </ul>
        <button type="button" onclick="addToMyMeals('${recipe.label}', ${recipe.calories}, ${recipe.totalNutrients.FAT.quantity.toFixed(2)}, ${recipe.totalNutrients.PROCNT.quantity.toFixed(2)}, ${recipe.totalNutrients.CHOCDF.quantity.toFixed(2)})">
            <strong style="font-size: 18px;">+</strong>Add to my meals
        </button>
        
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
        const addToMyMealsButtons = document.querySelectorAll('.recipe-details button');
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
<script>
const myButton = document.getElementById("myButton");

// Add an onclick event listener to the button
myButton.addEventListener("click", function() {
  // Change the button color to yellow when clicked
  myButton.style.backgroundColor = "yellow";
});
</script>
<script>
function getRecipeDetailsQueryParams(recipe) {
    const params = new URLSearchParams();
    params.append('image', recipe.image);
    params.append('label', recipe.label);
    params.append('source', recipe.source);
    params.append('preparation_time', recipe.totalTime);
    params.append('calories', recipe.calories.toFixed(2));
    params.append('fat', recipe.totalNutrients.FAT.quantity.toFixed(2));
    params.append('protein', recipe.totalNutrients.PROCNT.quantity.toFixed(2));
    params.append('carbohydrates', recipe.totalNutrients.CHOCDF.quantity.toFixed(2));
    params.append('servings', recipe.servings);
    params.append('totalWeight', recipe.totalWeight.toFixed(2));

    // Append array data (dietLabels, healthLabels, cautions) using a custom key
    recipe.dietLabels.forEach(label => params.append('dietLabels[]', label));
    recipe.healthLabels.forEach(label => params.append('healthLabels[]', label));
    recipe.cautions.forEach(caution => params.append('cautions[]', caution));

    // Append the ingredientLines as a comma-separated list
    const ingredientLinesString = recipe.ingredientLines.join(',');
    params.append('ingredientLines', ingredientLinesString);

    return params.toString();
}

    </script>
</body>
</html>
