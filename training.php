<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>FUEL FitHub - Training</title>
    <style>
        body {
            background-image: url('your_background_image.jpg'); /* Replace 'your_background_image.jpg' with the actual image file path */
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Add any other styles you need for the page */
        /* For example, styling the choices section */
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
        .section-content h1 {
            font-size: 40px;
            color:black;
        }

        .section-content button {
            border-radius:3px;
            background-color: #222831;
            color: #fff;
            padding: 30px 100px;
            border: none;
            cursor: pointer;
            font-size: 28px;
            margin: 20px;
        }
        .section-content button:hover {
            background-color: #424953;
        }
        .section-content {
              display: flex;
              flex-direction: row;
              justify-content: space-between;
              align-items: center;
              width: 85%;
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
              height:90%;
         
          }
          .mesh{
          background-color: #222831;
          width: 85%;
          margin-top:30px;
          text-align:center;
            color:white;
        }
        .section-content img{
            height:500px;
            width:500px;
            object-fit:cover;
        }
        
    </style>
</head>
<body>
<nav class="navbar">
        <a href="#" class="logo"><img class="logo" src="images/logo.png"><img>  </a>
        
        <!--{"Contact Us"}-->
        
          <a href="meal-maker.php">
            <button><i class="fas fa-utensils"></i> Make A Meal</button>
          </a>
          <a href="recipes.php">
            <button><i class="fas fa-book-open"></i> Recipes</button>
          </a>
          <a href="training.php">
            <button><i class="fas fa-dumbbell"></i> Training</button>
          </a>
  </nav>
    
<section id="section">
    <div class="mesh">
        <h1>Choose Your Training Option</h1>
    </div>
    <div class="section-content" style = "background-color:#393E46;font-size:24px;">
        <div class="left-portion">
            <img src="images/home_training.png">
            <button><a href="home_training.php" style="text-decoration:none;color:white;">Training at Home</a></button>
        </div>
        <div class="right-portion">
            <img src="images/gym_training.png">
            <button><a href="gym_training.php" style="text-decoration:none;color:white;">Training at the Gym</a></button>
        </div>
    </div>
</section>
    
</body>
</html>
