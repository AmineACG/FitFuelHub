
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
</style>
<body>
  <script>
    var Weight = <?php echo $weight ?>;
    var Height = <?php echo $height ?>;

    var hm = Height / 100;
    var BMI = Weight / (hm * hm);
    console.log(BMI);
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
        <h2 style="color:white;">Connected as <?php echo $user['username']; ?></h2>
    </nav>
    <main class="profile">
        <div class="container">
            <div class="left-portion">
                <div class="profile-header">
                    <h1 style="margin-left:12px;"><?php echo $fullName; ?></h1>
                    <p>Date Joined: <?php echo $joinDate; ?></p>
                </div>
                <div class="profile-info">
                    <h2>Gender: <?php echo $gender; ?></h2>
                    <h2>Age: <?php echo $birthday; ?></h2>
                    <h2>Height: <?php echo $height; ?> cm</h2>
                    <h2>Weight: <?php echo $weight; ?> kg</h2>
                </div>
            </div>
            <div class="right-portion">
            <div class="profile-picture">
                    <img id="profileImg"  src="<?php echo "images/profiles/$profile_picture"; ?>" alt="User Profile Picture">
                    <form action="upload_profile_picture.php" method="POST" enctype="multipart/form-data">
                    <input type="file" id="profileInput" name="profile_picture" accept="image/*" style="display: none;">
                        <button style="background-color:darkred;" type="submit" class="profile-buttons"><strong><i class="fas fa-upload"></i> Apply Changes</strong></button>
                    </form>
                </div>
              <div> 
              <button class="profile-buttons"><strong><i class="fas fa-dumbbell"></i> Training</strong></button>
              <button class="profile-buttons"><strong><i class="fas fa-carrot"></i> Diet</strong></button>
              <button class="profile-buttons"><strong><i class="fas fa-cog"></i> Settings</strong></button>
              <button class ="profile-buttons"><a href="logout.php" class="profile-buttons"><strong><i class="fas fa-sign-out-alt"></i> Log Out</strong></a></button>
            </div>
        </div>
    </main>
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
</html>

