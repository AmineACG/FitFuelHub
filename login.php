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
if (isset($_COOKIE['credentials'])) {
    $credentialsData = json_decode($_COOKIE['credentials'], true);
}else{
    $credentialsData['username'] = "";
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

  
    // Perform basic validation
    if (empty($username) || empty($password)) {
        $error = "<p style='color:red;'>Username and password are required.</p>";
    } else {
        // Check if the username exists in the database
        $query = "SELECT * FROM user WHERE username = :username";
        $stmt = $db->prepare($query);
        $stmt->execute([':username' => $username]);

        if ($stmt->rowCount() === 0) {
            $error = "Invalid username or password.";
        } else {
            // Verify the password
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                // Store user ID in session
                $_SESSION['user_id'] = $user['user_id'];
                $credentials = [
                    'username' => $_POST['username'],
                    'password' => $_POST['password'],
                ];
                $jsonData = json_encode($credentials);
                setcookie('credentials', $jsonData, time() + (86400 *3), '/'); // Cookie expires in 1 day     
                $user_id = $_SESSION['user_id']; // Retrieve user's ID from session or wherever
            
                // Redirect to the home page
                header("Location: recipes.php");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
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
    <title>Login</title>
</head>
<body>
    <nav class="navbar">
    <a href="home.php" class="logo">
        <a href="home.php" class="logo"><img class="logo" src="images/logo.png"><img>  </a>
        </a>
    </nav>
    <main class="sign-form">
        <div class="container">
            <form class="login-form" action="" method="POST">
                <h1>Login</h1>

                <?php if (isset($error)) { ?>
                    <p class="error-message"><?php echo $error; ?></p>
                <?php } ?>
                <div class="form-group">
                    <label for="username"><strong>Username</strong></label>
                    <input type="text" id="username" name="username" value="<?php echo $credentialsData['username'];?>" required>
                </div>
                <div class="form-group">
                    <label for="password"><strong>Password</strong></label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit">Login</button><br>
                <div>
                    <label><strong>Don't have an account? </strong><a href="signup.php"><strong><i>Sign Up</i></strong></a></label>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
