
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
</head>
<style>
  body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
}

.navbar {
  background-color: #393E46;
  padding: 10px;
  display: flex;
  align-items: center;
}

.navbar .logo {
  margin-right: auto;
  padding: 5px;
}

.login-button {
  border: none;
  border-radius: 25px;
  background-color: #222831;
  color: #fff;
  padding: 10px 20px;
  font-size: 18px;
  cursor: pointer;
}

.login-button:hover {
  background-color: #424953;
}

.profile {
  padding: 40px;
}

.container {
  max-width: 800px;
  margin: 0 auto;
}

.profile-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.profile-header h1 {
  font-size: 32px;
}
.edit-button {
  border: none;
  background-color: transparent;
  color: #222831;
  font-size: 16px;
  cursor: pointer;
}

.profile-details {
  display: flex;
  align-items: center;
  margin-top: 30px;
}

.profile-picture img {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  object-fit: cover;
  border: 5px solid #222831;
  box-shadow: 0 4px 8px brown ;
  margin-right: 30px;
}

.profile-info h2 {
  font-size: 28px;
  margin-bottom: 10px;
}

.profile-info p {
  font-size: 18px;
  margin: 5px 0;
}
</style>
<body>
    <nav class="navbar">
        <a href="home.php" class="logo">
            <img class="logo" src="images/FitHub.png" alt="FitHub Logo">
        </a>
        <button class="login-button">Connected as <?php echo $user['username']?></button>
    </nav>
    <main class="profile" style="background-color: #b9b9b9;">
        <div class="container" >
            <div class="profile-header" >
                <h1>My Profile</h1>
                <button class="edit-button">Edit Profile</button>
                <a href ="logout.php" ><button class="edit-button">Log Out</button></a>

            </div>
            <div class="profile-details" >
                <div class="profile-picture">
                    <img src="images/cyb_enter.jpg" alt="User Profile Picture">
                </div>
                <div class="profile-info">
                    <h2><?php echo $fullName; ?></h2>
                    <p>Email: <?php echo $email; ?></p>
                    <p>Gender: <?php echo $gender; ?></p>
                    <p>Birthday: <?php echo $birthday; ?></p>
                    <p>Height: <?php echo $height; ?> cm</p>
                    <p>Weight: <?php echo $weight; ?> kg</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
