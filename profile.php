
<?php
session_start();
// Check if the user is already logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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

// Retrieve user data from the database
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
$profile_picture = $user['profile_picture'];
if (isset($_COOKIE['custom_meal'])) {
        $cookieData = json_decode($_COOKIE['custom_meal'], true);
    } else {
      $cookieData['calories'] = 0;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $user['username']; ?></title>
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color:white;
  }

  .navbar {
    background: linear-gradient(to right, black, #222831);
    padding: 10px;
    display: flex;
    align-items: center;
  }

  .navbar .logo {
    margin-right: auto;
    padding: 5px;
  }


  .login-button:hover {
    background-color: #424953;
  }

  .profile {
    padding:0px;
    background-color:white;
  }

  .container {
    display: flex;
    width: 95%;
    margin: 10px auto;
    
    border-radius: 8px;
  }

  .left-portion {
    flex: 80%;
    background-color: #f0f0f0;
    padding:0px;
  }

  .right-portion {
    flex: 20%;
    padding: 20px;
    margin-left:20px;
  }

  .profile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #222831;
    color: #fff;
    padding: 15px;
    border-top-left-radius: 8px;
  }

  .profile-header h1 {
    font-size: 32px;
    margin: 0;
  }

  .profile-info {
    margin-top: 30px;
    text-align:left;
    margin-left:22px;
  }

  .profile-info h2 {
    font-size: 28px;
    margin-bottom: 10px;
  }

  .profile-info p {
      font-size: 18px;
      margin: 5px 0;
      color: #333; /* Text color */
      line-height: 1.6; /* Line height for better readability */
    }

  .profile-picture img {
    width: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid #222831;
    box-shadow: 0 4px 8px brown;
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
  .profile-buttons {
    background-color: #222831;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
    margin-bottom: 5px;
    width:95%;
  }
  .logo{
      height:128px;
    }
    .health-bar {
      margin-top:10px;
  width: 300px;
  height: 30px;
  border-radius:14px;
  border: 2px solid #000;
  position: relative;
  }

  .health-value {
    color:black;
    position: absolute;
    top: 120%;
    left: 50%;
    transform: translateX(-50%);
    cursor:default;
  }


  .health-fill {
    border-radius:10px;
    height: 100%;
    background: linear-gradient(to left, black, darkred);
    box-shadow:0px 0px 15px red;
  }
  .sidebuttons {
        position: absolute;
        right: 350px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
  width:250px;
    }

    .sideButtons {
        margin-bottom: 10px;
        border-radius:2px;
        font-size:30px;
        background: linear-gradient(to left, black, #222831);
        color:white;
    }
    .hoverable-btn {
      padding:10px 0px;
      margin-bottom: 10px;
        border-radius:2px;
        font-size:30px;
        width: inherit; /* Set your desired width for the buttons on hover */
        transition: width 0.3s ease; /* Add transition for smooth width change */
        
        background: linear-gradient(to left, #222831, black);
        color:white;
      }

      .hoverable-btn:hover {
          width: 290px; /* Set your desired width for the buttons on hover */

      }.popup {
          display: none;
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(0, 0, 0, 0.5);
          z-index: 1;
      }

      .popup-content {
          background-color: #f9f9f9;
          margin: 20% auto;
          padding: 20px;
          border: 1px solid #888;
          width: 60%;
      }

      .close {
          color: #aaa;
          float: right;
          font-size: 28px;
          font-weight: bold;
          cursor: pointer;
      }

      .close:hover {
          color: #000;
      }

      /* Adjust popup size and position as needed */
      @media screen and (max-width: 600px) {
          .popup-content {
              width: 80%;
          }
      }
      .radio-container {

    max-width: 550px;
    }

    .radio-wrapper {
    margin-bottom: 20px;
    }

    .radio-button {
    display: flex;
    align-items: center;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    }

    .radio-button:hover {
    transform: translateY(-2px);
    }

    .radio-button input[type="radio"] {
    display: none;
    }

    .radio-checkmark {
    display: inline-block;
    position: relative;
    width: 16px;
    height: 16px;
    margin-right: 10px;
    border: 2px solid #333;
    border-radius: 50%;
    }

    .radio-checkmark:before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #333;
    transition: all 0.2s ease-in-out;
    }

    .radio-button input[type="radio"]:checked ~ .radio-checkmark:before {
    transform: translate(-50%, -50%) scale(1);
    }

    .radio-label {
      color:gray;
    font-size: 16px;
    font-weight: 600;
    }
    .popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1;
    }

    .popup-content {
        background-color: #f9f9f9;
        margin: 20% auto;
        padding: 20px;
        border: 1px solid #888;
        width:fit-content;
        align-items:center;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover {
        color: #000;
    }
    #updateForm{
      width:300px;
    }
    #updateForm label{
      width:300px;
      text-align:center;
      margin-bottom:10px;
    } 
    #updateForm input{
      width:290px;
      text-align:center;
      font-size:20px;
    }
</style>
<body>

<div id="activityLevelPopup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeActivityLevelPopup()">&times;</span>
        <div class="radio-container">
    <div class="radio-wrapper">
        <label class="radio-button">
            <input type="radio" name="activity_level" id="activity_sedentary" value="1">
            <span class="radio-checkmark"></span>
            <span class="radio-label">Sedentary (little to no exercise)</span>
        </label>
    </div>

        <div class="radio-wrapper">
            <label class="radio-button">
                <input type="radio" name="activity_level" id="activity_light" value="2">
                <span class="radio-checkmark"></span>
                <span class="radio-label">Lightly active (light exercise/sports 1-3 days/week)</span>
            </label>
        </div>
        <div class="radio-wrapper">
            <label class="radio-button">
                <input type="radio" name="activity_level" id="activity_moderate" value="3">
                <span class="radio-checkmark"></span>
                <span class="radio-label">Moderately active (moderate exercise/sports 3-5 days/week)</span>
            </label>
        </div>

        <div class="radio-wrapper">
            <label class="radio-button">
                <input type="radio" name="activity_level" id="activity_very_active" value="4">
                <span class="radio-checkmark"></span>
                <span class="radio-label">Very active (hard exercise/sports 6-7 days a week)</span>
            </label>
        </div>
        <div class="radio-wrapper">
            <label class="radio-button">
                <input type="radio" name="activity_level" id="activity_extra_active" value="5">
                <span class="radio-checkmark"></span>
                <span class="radio-label">Extra active (very hard exercise/sports & physical job or training)</span>
            </label>
        </div>
    </div> 
        <button style="float:center;"onclick="applyActivityLevel()">Apply</button>
    </div>
</div>
  <script>
    var Weight = <?php echo $weight ?>;
    var Height = <?php echo $height ?>;
    var Age = <?php echo $birthday ?>;
    var Gender = "<?php echo $gender ?>";
    console.log(Gender);
    var hm = Height / 100;
    var BMI = Weight / (hm * hm);
    console.log(BMI.toFixed(2));
    </script>
    <nav class="navbar">
        <a href="home.php" class="logo">
            <img class="logo" src="images/logo.png" alt="FitHub Logo">
        </a>
          <a href="meal-maker.php">
            <button><i class="fas fa-utensils"></i> Make A Meal</button>
          </a>
          <a href="recipes.php">
            <button><i class="fas fa-book-open"></i> Recipes</button>
          </a>
          <a href="#">
            <button><i class="fas fa-dumbbell"></i> Training</button>
          </a>
        <h2 style="color:white;cursor:default;font-size:16px;margin-left:100px;margin-right:40px;">Connected as <?php echo $user['username']; ?></h2>
    </nav>
    <main class="profile">
        <div class="container">
            <div class="left-portion">
                <div class="profile-header">
                    <h1 style="margin-left:12px;"><?php echo $fullName; ?></h1>
                    <p>Date Joined: <?php echo $joinDate; ?></p>
                </div>
                <div class="profile-info">
                <h2 style="font-size:24px;">Daily Calories Goal : 
             <span id="result">Choose Your Activity Level</span></input>
                <div class="health-bar">
                  <div class="health-fill" id="healthBar"></div>
                  <div class="health-value"><?php echo $cookieData['calories'] ?> Kcal</div>

                </div>


</div>           
<div  style="text-align: left;margin-left:20px;">
</div> 


            </div>

            <div class="sidebuttons">
              
              <button class="hoverable-btn"><?php echo $weight; ?> Kg</button>  
              <button class="hoverable-btn"><?php echo $height; ?> Cm</button>  
              <button class="sideButtons"><?php echo $gender; ?></button>  
              <button class="sideButtons"> <?php echo $birthday;?> Years</button>  
            </div>
            <div id="updatePopup" class="popup">
              <div class="popup-content">
                  <span class="close" onclick="closePopup()">&times;</span>
                  <form id="updateForm" action="update-info.php" method="POST">
                      <label for="weightInput">Weight:</label>
                      <input type="number" id="weightInput" name="weight" step="0.01" placeholder="Enter new weight (kg)" value="<?php echo $weight; ?>">
                      <label for="heightInput">Height:</label>
                      <input type="number" id="heightInput" name="height" step="0.01" placeholder="Enter new height (cm)" value="<?php echo $height; ?>">
                      <button type="submit">Update</button>
                  </form>
              </div>
          </div>
            <div class="right-portion">
            <div class="profile-picture">
                    <img id="profileImg"  src="<?php echo "images/profiles/$profile_picture"; ?>" alt="User Profile Picture">
                    <form action="upload_profile_picture.php" method="POST" enctype="multipart/form-data">
                    <input type="file" id="profileInput" name="profile_picture" accept="image/*" style="display: none;">
                        <button style="background-color:darkred;" type="submit" class="profile-buttons"><strong><i class="fas fa-upload"></i> Apply Profile Picture</strong></button>
                      </form>
                </div>
              <div> 
              <button id="weightBtn" class="profile-buttons"><strong><i class="fas fa-dumbbell"></i> Update Weight</strong></button>
              <button class="profile-buttons" onclick="openActivityLevelPopup()"><strong><i class="fas fa-fire"></i> Activity Level</strong></div>
              <button class="profile-buttons"><strong><i class="fas fa-cog"></i> Settings</strong></button>
              <button class ="profile-buttons"><a href="logout.php" class="profile-buttons"><strong><i class="fas fa-sign-out-alt"></i> Log Out</strong></a></button>
            </div>
            
        </div>
    </main>
    <section style="background-color: #f0f0f0;">
    <div style="background-color: #222831;width: 100%;text-align:center;color:white;margin-top:100px;">
    <h1>Analytics</h1>
    <p style="font-size:20px;">Here you can find Your weekly progress wether you are on a caloric surplus or a protein diet, Just click on calories label to show detailed values of other macronutrients</p>
  </div>
    <div style="width: 80%; margin: 0 auto;background-color: #fff;">
    <canvas id="myChart"></canvas>
    <div class="right-portion">
    <div>
    <button id="addDataButton">Update The Chart </button>
    </div>
    <script>

        var days = 0; // Initialize days for the label button

        var currentDate = new Date();
        var currentDayNumber = currentDate.getDay();
        var daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        var labels = [];

        // Loop through the days of the week
        for (var i = 0; i < 7; i++) {
            var dayIndex = (currentDayNumber + i) % 7;
            labels.push(daysOfWeek[dayIndex]);

        }

        var chartData = {
            labels: labels,
            datasets: [
                {
                    label: "Calories",
                    data: getStoredData("calories") || [], // Retrieve stored data or initialize with empty array
                    borderColor: "red",
                    yAxisID: 'y', // Use the main y-axis
                    fill: false
                },
                {
                    label: "Carbohydrates",
                    data: getStoredData("carbohydrates") || [], // Retrieve stored data or initialize with empty array
                    borderColor: "green",
                    yAxisID: 'y1', // Use the main y-axis
                    fill: false
                },
                {
                    label: "Fat",
                    data: getStoredData("fat") || [], // Retrieve stored data or initialize with empty array
                    borderColor: "gold",
                    yAxisID: 'y1', // Use the main y-axis
                    fill: false
                },
                {
                    label: "Proteins",
                    data: getStoredData("protein") || [], // Retrieve stored data or initialize with empty array
                    borderColor: "rgba(75, 192, 192, 1)",
                    yAxisID: 'y1', // Use the main y-axis
                    fill: false
                }
            ]
        };

        var ctx = document.getElementById('myChart').getContext('2d');
        var myLineChart = new Chart(ctx, {
    type: 'line',
    data: chartData,
            options: {
                scales: {
                    y: {
                  type: 'linear', // Use linear scale for the main y-axis
                  position: 'left',
                  beginAtZero: true // You can adjust other scale options here
              },y1: {
                  type: 'linear', // Use linear scale for the second y-axis
                  position: 'right',
                  beginAtZero: true // You can adjust other scale options here
                }
              }
            }
        });
        var addDataButton = document.getElementById('addDataButton');
        console.log("button is On");

        addDataButton.addEventListener('click', function() {
          
            var lastClickTimestamp = localStorage.getItem('lastClickTimestamp');
            var currentTime = new Date().getTime();
            var oneDayInMilliseconds = 0; // One day in milliseconds

            // Check if a day has passed since the last click
            if (1) {
                // Update the chart data and store it in localStorage
                chartData.datasets[0].data.push(<?php echo $cookieData['calories']; ?>);
                chartData.datasets[1].data.push(<?php echo $cookieData['carbohydrates']; ?>);
                chartData.datasets[2].data.push(<?php echo $cookieData['fat']; ?>);
                chartData.datasets[3].data.push(<?php echo $cookieData['protein']; ?>);

                // Store the updated data in localStorage
                storeData("calories", chartData.datasets[0].data);
                storeData("carbohydrates", chartData.datasets[1].data);
                storeData("fat", chartData.datasets[2].data);
                storeData("protein", chartData.datasets[3].data);

                // Store the current timestamp as the last click timestamp
                localStorage.setItem('lastClickTimestamp', currentTime);

                myLineChart.update();
            } else {
                console.log("You can only add data once per day.");
            }
        });

        function storeData(key, data) {
            localStorage.setItem(key, JSON.stringify(data));
        }

        function getStoredData(key) {
            var storedData = localStorage.getItem(key);
            return storedData ? JSON.parse(storedData) : null;
        }
    </script>
  </section>
</body>
<script>
    // Get references to the profile picture element and the file input element
    const profileImg = document.getElementById('profileImg');
    const profileInput = document.getElementById('profileInput');

    // Trigger the click event of the file input when the profile picture is clicked
    profileImg.addEventListener('click', () => {
        profileInput.click();
    });

    // Update the profile picture preview when a new image is selected
    profileInput.addEventListener('change', () => {
        const file = profileInput.files[0];
        if (file) {
            const reader = new FileReader();

            reader.onload = () => {
                profileImg.src = reader.result;
            };

            reader.readAsDataURL(file);
        }
    });
</script>
<script>
  function updateHealthBar(calories, totalCalories) {
    const healthBar = document.getElementById('healthBar');
    if (healthBar) {
        const percentage = (calories / totalCalories) * 100;
        
        // Limit the health bar width to the totalCalories percentage
        const limitedPercentage = Math.min(percentage, 100);
        
        healthBar.style.width = limitedPercentage + '%';
    } else {
        console.error('healthBar element not found.');
    }
}
   // Function to open the activity level popup
   function openActivityLevelPopup() {
        const popup = document.getElementById('activityLevelPopup');
        popup.style.display = 'block';
    }

    // Function to close the activity level popup
    function closeActivityLevelPopup() {
        const popup = document.getElementById('activityLevelPopup');
        popup.style.display = 'none';
    }

    // Function to apply the selected activity level and update the calorie goal
    function applyActivityLevel() {
        // Get the selected activity level value
        const activityLevelRadios = document.getElementsByName('activity_level');
        let selectedActivityLevel;
        for (const radio of activityLevelRadios) {
            if (radio.checked) {
                selectedActivityLevel = parseFloat(radio.value);
                break;
            }
        }

        // Calculate the total daily calorie goal based on the selected activity level
        const totalCalories = BMR * selectedActivityLevel;

        // Display the result
        const resultElement = document.getElementById('result');
        resultElement.textContent = `${totalCalories.toFixed(2)} calories`;
        updateHealthBar(<?php echo $cookieData['calories']?>, totalCalories); // The first argument is just a placeholder for calories
        // Close the popup after applying the selected activity level
        closeActivityLevelPopup();
    }
    
  </script>
<script>
    // Function to open the popup
    function openPopup() {
        const popup = document.getElementById('updatePopup');
        popup.style.display = 'block';
    }

    // Function to close the popup
    function closePopup() {
        const popup = document.getElementById('updatePopup');
        popup.style.display = 'none';
    }

    // Add event listener for weight button click
    document.getElementById('weightBtn').addEventListener('click', () => {
        openPopup();
    });

    // Add event listener for height button click
    document.getElementById('heightBtn').addEventListener('click', () => {
        openPopup();
    });
</script>
<script>
  
  var BMR = 0;
  //Daily Calorie goal 
  if(Gender == 'Male'){
    //BMR for Males
     BMR = 88.362 + (13.397 * Weight) + (4.799 * Height) - (5.677 * Age);
  }else{
    //BMR For Females
     BMR = 447.362 + (9.247 * Weight) + (3.098 * Height) - (4.330 * Age);
  }
    console.log("BMR = " + BMR.toFixed(2));
    
  </script>
</html>

