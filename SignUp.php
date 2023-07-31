<?php
session_start();

// Check if the user is already logged in and redirect to the home page
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sex = $_POST['sex'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $birthday = $_POST['birthday'];
    $plan = $_POST['plan'];
    // Perform basic validation
    if (empty($username) || empty($email) || empty($password) || empty($sex) || empty($height) || empty($weight)|| empty($birthday)){
        $error = "All fields are required.";
    } else {
        // Check if the username is already taken
        $query = "SELECT * FROM user WHERE username = :username";
        $stmt = $db->prepare($query);
        $stmt->execute([':username' => $username]);

        if ($stmt->rowCount() > 0) {
            $error = "Username is already taken.";
        } else {
            // Insert user data into the database
            $query = "INSERT INTO user (username, email, password, sex,height,weight,birthday,created_at,plan) VALUES (:username, :email, :password, :sex, :height, :weight,:birthday, NOW() ,:plan)";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => password_hash($password, PASSWORD_DEFAULT),
                ':sex' => $sex,
                ':height' => $height,
                ':weight' => $weight,
                ':birthday' => $birthday,
                ':plan' => $plan

            ]);

            // Redirect to the login page
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <nav class="navbar">
    <a href="home.php" class="logo">
            <img class="logo" src="images/logo.png"><img>  
        </a>
    </nav>
    <main class="sign-form">
        <div class="container" style="margin:0px;">
            <form class="login-form" action="" method="POST">
                <h1>Sign Up</h1>

                <?php if (isset($error)) { ?>
                    <p class="error-message"><?php echo $error; ?></p>
                <?php } ?>

                <div class="form-group">
                    <label for="username"><strong>Username</strong></label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email"><strong>Email</strong></label>
                    <input type="text" id="email" name="email"  required>
                </div>
                <div class="form-group">
                    <label for="password"><strong>Password</strong></label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="birthday"><strong>Age   </strong></label>
                    <input type="number" id="birthday" name="birthday" required>
                </div>
                <div class="form-group">

                    <label for="sex">Male</label>
                    <input type="radio" id="sex" name="sex" value="Male" required>

                    <label for="sex">Female</label>
                    <input type="radio" id="sex" name="sex" value="Female" required>

                </div>
                <div class="form-group">
                    <label for="height"><strong>height</strong></label>
                    <input type="number" id="height" name="height" required>Cm
                </div>
                <div class="form-group">
                    <label for="weight"><strong>weight</strong></label>
                    <input type="number" id="weight" name="weight" required>Kg
                </div>
                <div class="form-group">
                    
                    <label for="plan">Bulk</label>
                    <input type="radio" id="plan" name="plan" value="2" required>

                    <label for="plan">Cut</label>
                    <input type="radio" id="plan" name="plan" value="1" required>

                </div>
                <button type="submit">Sign Up</button><br>
                <div>
                    <label><strong>Already Have An Account? </strong><a href="login.php"><strong><i>Login</i></strong></a></label>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
