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

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Retrieve the user's username from the database
    $query = "SELECT username FROM user WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $loggedInUser = $user['username'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>FUEL FitHub</title>
    <style>
        body {
            background-color: #EEEEEE;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #222831;
            display: flex;
            align-items: center;
        }

        .navbar button {
            border-radius: 0px;
            background-color: #222831;
            color: #fff;
            padding:fit-content 50px;
            border: none;
            cursor: pointer;
            font-size: 20px;
            margin-left: auto; /* Add this property to make the buttons float to the right */
        }

        .placeholder {
            height: 40px;
            width: 40px;
            margin-right: 10px;
        }

        /* Section Styles */
        section {
            height: calc(100vh - 50px);
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            scroll-snap-align: start;
            color: black;
        }

        h1 {
            font-size: 40px;
        }

        .section-content {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            width: 95%;
            height: 90%;
            margin: 0px;
            color:white;
        }

        .left-portion,
        .right-portion {
            flex-basis: 45%;
            padding: 20px;
            text-align: center;
            background-color: inherit;

        }

        /* Button Styles */
        button {
            border-radius: 50px;
            background-color: #222831;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 18px;
            margin-top: 10px;
        }

        .button:hover {
            background-color: #424953;
        }

        /* Global Styles */
        .container {
            margin-top: 0;
            margin-right: 150px;
        }

        .login-form {
            text-align: center;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .form-group {
            margin-bottom: 10px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-top: 15px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 9px;
            height: 17px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="number"] {
            width: 25%;
            height: 25px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .antimsp {
            margin-top: 20px;
        }

        .sign-form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 80%;
            margin: 0;
        }

        #added-foods {
            margin-bottom: 20px;
            padding: 10px;
            height: 100%;
            background-color: #ccc;
            border-radius: 5px;
            border: 2px solid #393E46;
        }

        /* Custom Styles */
        .section2-image,
        .section3-image {
            width: 500px;
            height: 500px;
            object-fit: cover;
            border-radius: 50%;
            
        }
        button{padding:20px 20px;}
        .mesh{
  background-color: #222831;
  width: 95%;
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
    <?php if (isset($_SESSION['user_id'])) { ?>
      <a href="profile.php"><button class="login-button"><?php echo $loggedInUser; ?></button></a>
    <?php } else { ?>
    <a href="login.php"><button class="login-button">Log In</button></a>
    <?php } ?>
  </nav>
  
  <div class="left-portion">
    <h1>The ULTIMATE Destination for Real-Time<br> Transformation and Unparalleled<br> Experiences..</h1>
    <button class="button">Learn More</button>
  </div>

  <section id="section2">
  <div class="mesh">
        <h1></h1>
    </div>
    <div class="section-content" style = "background-color:#393E46; color:#ccc;font-size:22px;">
    
      <div class="left-portion">
        <!-- Left portion content in Section 2 -->
        <h2>Prepared recipes </h2>
        <p>Explore a treasure trove of diverse and delicious recipes that will tantalize your taste buds and satisfy your cravings. From savory main courses to delectable desserts, our collection covers a wide array of culinary delights to suit every palate.</p>
        <button class="button" style="font-size:28px;">Explore Our Recipes</button>
      </div>
      
      <div class="right-portion" >
        <!-- Right portion content in Section 2 -->
        <img class="section2-image"  src="images/cyb_enter.jpg" alt="Section 2 Image">
      </div>
    </div>
  </section>
  
  <section id="section3">
  <div class="mesh">
        <h1></h1>
    </div>
    <div class="section-content" style = "background-color:#393E46;font-size:24px;">
      <div class="left-portion" >
      <img  class="section3-image" src="images/fem_eating.jpg"  alt="Section 3 Image">

      </div>
      
      <div class="right-portion">
        <!-- Right portion content in Section 3 -->
        
        <!-- Left portion content in Section 3 -->
        <h2>Meal Maker</h2>
        <p>Create your perfect meal with our Meal Maker tool:</p>
        <ul>
          <li><span class="placeholder"></span>Select from a wide variety of food items.</li>
          <li><span class="placeholder"></span>Customize portion sizes and ingredients.</li>
          <li><span class="placeholder"></span>Calculate nutritional information for your meal.</li>
          <li><span class="placeholder"></span>Add to your meal diary for easy tracking.</li>
        </ul>
        <a href ="meal-maker.php"><button class="button" style="font-size:28px;">Start Building Your Meal</button></a>
      </div>
    </div>
  </section>
  <section id="section2">
  <div class="mesh">
        <h1></h1>
    </div>
    <div class="section-content" style = "background-color:#393E46; color:#ccc;font-size:22px;">
    
      <div class="left-portion">
        <!-- Left portion content in Section 2 -->
        <h2>Roles and Functionalities</h2>
        <p>At FUEL FitHub, we offer a wide range of roles and functionalities to cater to your fitness journey:</p>
        <ul>
          <li><span class="placeholder"></span>Personal Trainer: Get expert guidance and personalized workout plans.</li>
          <li><span class="placeholder"></span>Nutritionist: Receive tailored meal plans and dietary advice.</li>
          <li><span class="placeholder"></span>Meal Planner: Plan and track your meals for optimal nutrition.</li>
          <li><span class="placeholder"></span>Workout Tracker: Monitor your progress and stay motivated.</li>
        </ul>
        <button class="button" style="font-size:28px;">Explore Personalized Fitness</button>
      </div>
      
      <div class="right-portion" >
        <!-- Right portion content in Section 2 -->
        <img class="section2-image"  src="images/cyb_enter.jpg" alt="Section 2 Image">
      </div>
    </div>
  </section>
</body>
</html>
