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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>FUEL FitHub</title>
    <style>
      h2{
        font-size :55px;
        color:white;
      }
        body {
            background-color: #EEEEEE;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .navbar {
          background: linear-gradient(to right, black, #222831);
          padding: 10px;
          display: flex;
          align-items: center;
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
          .popup {
          display: none;
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(0, 0, 0, 0.7);
          z-index: 9999;
        }

        .popup-content {
          background-color: #fff;
          max-width: 400px;
          margin: 100px auto;
          padding: 20px;
          border-radius: 5px;
        }

        .close {
          position: absolute;
          top: 10px;
          right: 20px;
          font-size: 24px;
          cursor: pointer;
        }

      /* Add blur effect to the background when the popup is shown */
      
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
            width: 92%;
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
        .section2-image{
            width: 500px;
            height: 500px;
            object-fit: cover;
            border-radius: 50%;
            
        }
        .section3-image {
            width: 750px;
            height: 500px;
            object-fit: cover;
            border-radius: 10%;

        }
        button{padding:20px 20px;}
            .mesh{
          background-color: #222831;
          width: 95%;
        }
        footer {
          background-color: #f5f5f5;
          padding: 20px 0;
        }

        .footer-container {
          max-width: 1200px;
          margin: 0 auto;
          display: flex;
          justify-content: space-between;
          align-items: flex-start;
          color:black;

        }

        .footer-left,
        .footer-right {
          width: 45%;
          
        }

        .footer-left h4,
        .footer-right h4 {
          margin-bottom: 10px;
        }

        .footer-bottom {
          background-color: #ebebeb;
          padding: 10px 0;
          text-align: center;
        }
        .logo{
          height:128px;
        }
    </style>
</head>
<body>
  <nav class="navbar">
        <a href="#" class="logo"><img class="logo" src="images/logo.png"><img>  </a>
        
        <!--{"Contact Us"}-->
        <?php if (isset($_SESSION['user_id'])) { ?>
          <a href="meal-maker.php">
            <button><i class="fas fa-utensils"></i> Make A Meal</button>
          </a>
          <a href="recipes.php">
            <button><i class="fas fa-book-open"></i> Recipes</button>
          </a>
          <a href="#">
            <button><i class="fas fa-dumbbell"></i> Training</button>
          </a>
          <button><a href="profile.php"><i class="fas fa-user"></i> <?php echo $loggedInUser; ?></a></button>
        <?php } else { ?>
          <button onclick="showPopup()" style="color:inherit;background-color:white;">
            <i class="fas fa-sign-in-alt"></i> <a style="">Log In</a>
          </button>
          <a style="" href="SignUp.php">
            <button><i class="fas fa-user-plus"></i> Sign Up</button>
          </a>
        <?php } ?>
  </nav>
      <div id="loginPopup" class="popup">
      <div class="popup-content">
      <img style="width:380px;"class="logo" src="images/logo.png"><img>
        <form action="login.php" method="post">
          <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
          </div>
          <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
          </div>
          <button style="width: 97%;" type="submit">Login</button>
          <div>
            <label><strong>Don't have an account? </strong><a href="signup.php"><strong><i>Sign Up</i></strong></a></label>
          </div>
        </form>
        <button class="close" onclick="closePopup()">X</button>
      </div>
    </div>
  <div class="left-portion">
    <h1>The ULTIMATE Destination for Real-Time<br> Transformation and Unparalleled<br> Experiences..</h1>
    <h3>Fithub | Whit Clear Goals ... Comes Discipline</h3>
    <?php if (isset($_SESSION['user_id'])) { ?>

      <?php } else { ?>
        <a href ="SignUp.php"><button style="font-size:30px;" class="button">Start for free   </button></a>
        <?php } ?>
  </div>
<script>
  function showPopup() {
    document.getElementById("loginPopup").style.display = "block";
    document.body.classList.add("blur-background");
  }

  function closePopup() {
    document.getElementById("loginPopup").style.display = "none";
    document.body.classList.remove("blur-background");
  }
  </script>
  <section id="section2">
  <div class="mesh">
        <h1></h1>
    </div>
    <div class="section-content" style = "background-color:#393E46; color:#ccc;font-size:22px;">
    
      <div class="left-portion">
        <!-- Left portion content in Section 2 -->
        <h2>Prepared recipes </h2>
        <p>Explore a treasure of diverse and delicious recipes that will tantalize your taste buds and satisfy your cravings. From savory main courses to delectable desserts, our collection covers a wide array of culinary delights to suit every palate.</p>
        <a href ="recipes.php"><button class="button" style="font-size:28px;">Explore Our Recipes</button></a>
      </div>
      
      <div class="right-portion" >
        <!-- Right portion content in Section 2 -->
        <img class="section2-image"  src="images/recipes.jpg" alt="Section 2 Image">
      </div>
    </div>
  </section>
  
  <section id="section3">
  <div class="mesh">
        <h1></h1>
    </div>
    <div class="section-content" style = "background-color:#393E46;font-size:24px;">
      <div class="left-portion" >
      <img  class="section3-image" src="images/mealmaker.png"  alt="Section 3 Image">

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
        <img class="section2-image"  src="images/Fithub.png" alt="Section 2 Image">
      </div>
    </div>
  </section>
  <footer>
    <div class="footer-container">
      <div class="footer-left">
        <h4>About Us</h4>
        <p>Welcome to our news website, your trusted source for timely and reliable news coverage. At OnTheDot, we are committed to delivering high-quality journalism and keeping you informed about the latest happenings across various topics.</p>
      </div>
      <div class="footer-right">
        <h4>Contact Us</h4>
        <p>Email: med.amine.birje@example.com</p>
        <p>Phone: 0772441117</p>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2023 FitFuelHub. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>
