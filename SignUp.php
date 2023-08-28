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
    $emailParts = explode('@', $email);
    if (count($emailParts) !== 2 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    }
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $sex = $_POST['sex'];
    $birthday = filter_var($_POST['birthday'], FILTER_SANITIZE_NUMBER_INT);
    $height = filter_var($_POST['height'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $weight = filter_var($_POST['weight'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    $plan = $_POST['plan'];

    // Perform  validation
    
    
    if (!filter_var($height, FILTER_VALIDATE_FLOAT, array('options' => array('min_range' => 1, 'max_range' => 300)))) {
      $error = "Invalid height.";
    }
    
    if (!filter_var($weight, FILTER_VALIDATE_FLOAT, array('options' => array('min_range' => 1)))) {
        $error = "Invalid weight.";
    }
    if (strlen($password) < 8 || !preg_match('/\d/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[A-Z]/', $password) || !preg_match('/[\W_]/', $password)) {
      $error = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one digit, and one special character.";
    }
    if (empty($username) || empty($email) || empty($password) || empty($sex) || empty($height) || empty($weight) || empty($birthday)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirmPassword) {
        $error = "<div style='color:red;text-align:center;'>Passwords do not match.</div>";
    } else {
      
        // Check if the username is already taken
            $queryUsername = "SELECT * FROM user WHERE username = :username";
            $stmtUsername = $db->prepare($queryUsername);
            $stmtUsername->execute([':username' => $username]);
            
            $queryEmail = "SELECT * FROM user WHERE email = :email";
            $stmtEmail = $db->prepare($queryEmail);
            $stmtEmail->execute([':email' => $email]);
            
            if ($stmtUsername->rowCount() > 0) {
                $error = "Username is already taken.";
            } elseif ($stmtEmail->rowCount() > 0) {
                $error = "Email is already taken.";
            } else {
              if (isset($error)) {
                // If there's an error, display the message and prevent form submission
              } else {
            // Insert user data into the database
            $query = "INSERT INTO user (username, email, password, sex, height, weight, birthday, created_at, plan) VALUES (:username, :email, :password, :sex, :height, :weight, :birthday, NOW(), :plan)";
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
<style>
  
   #passwordPolicyMessage {
        font-size: 12px;
        color: red;
        margin-top: 5px;
        display: block;
    }
    .form {
  font-size:16px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  max-width: 400px;
  background-color: #fff;
  padding: 20px 50px;
  border-radius: 20px;
  position: relative;
  margin-left:8%;
  }

  .title {
    font-size: 28px;
    color: darkred;
    font-weight: 600;
    letter-spacing: -1px;
    position: relative;
    display: flex;
    align-items: center;
    padding-left: 30px;
  }

  .title::before,.title::after {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    border-radius: 50%;
    left: 0px;
    background-color: darkred;
  }

  .title::before {
    width: 18px;
    height: 18px;
    background-color: darkred;
  }

  .title::after {
    width: 18px;
    height: 18px;
    animation: pulse 1s linear infinite;
  }

  .message, .signin {
    color: rgba(88, 87, 87, 0.822);
    font-size: 14px;
  }

  .signin {
    text-align: center;
  }

  .signin a {
    color: darkred;
  }

  .signin a:hover {
    text-decoration: underline darkred;
  }

  .flex {
    display: flex;
    width: 100%;
    gap: 50px;
  }

  .form label {
    position: relative;
  }

  .form label .input {
      font-size:18px;
    width: 100%;
    padding: 10px 10px 20px 10px;
    outline: 0;
    border: 1px solid rgba(105, 105, 105, 0.397);
    border-radius: 10px;
  }

  .form label .input + span {
    position: absolute;
    left: 10px;
    top: 15px;
    color: grey;
    font-size: 0.9em;
    cursor: text;
    transition: 0.3s ease;
  }

  .form label .input:placeholder-shown + span {
    top: 15px;
    font-size: 0.9em;
  }

  .form label .input:focus + span,.form label .input:valid + span {
    top: 30px;
    font-size: 0.7em;
    font-weight: 600;
  }

  .form label .input:valid + span {
    color: green;
  }

  .submit {
    border: none;
    outline: none;
    background-color: darkred;
    padding: 10px;
    border-radius: 10px;
    color: #fff;
    font-size: 16px;
    transform: .3s ease;
  }

  .submit:hover {
    background-color: rgb(56, 90, 194);
  }

  @keyframes pulse {
    from {
      transform: scale(0.9);
      opacity: 1;
    }

    to {
      transform: scale(1.8);
      opacity: 0;
    }
  }
  .radio-container {

    max-width: 300px;
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
  body {
    background-image: url('images/background.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
  }
  .error-message {
        background-color: #ffebee; 
        border: 1px solid #c62828; 
        color: #c62828;
        padding: 10px; 
        border-radius: 5px; 
        margin-bottom: 10px; 
    }
    @media (max-width: 768px) {
      body {
    background-image: none;

  }
      .flex{
        width:90%;
        gap:35px;
      }
            .form {
              border-radius:0px;
                padding: 20px;
                max-width: 100%; /* Make the form a bit smaller on smaller screens */
                margin: 0 auto;
            }
            .title {
                font-size: 20px;
            }
            .input {
                padding: 8px;
                max-width:320px;
                font-size: 13px;
            }
            .radio-button {
                font-size: 12px;
            }
            /* ... Add more adjustments as needed ... */
        }
    </style>
<body>
    <nav class="navbar">
    <a href="home.php" class="logo">
            <img class="logo" src="images/logo.png"><img>  
        </a>
    </nav>
        <form class="form" action="" method="POST">
    <p class="title">Register </p>
    <?php if (isset($error)) { ?>
            <div class="error-message"><?php echo $error; ?></div>
                <?php } ?>
    <div class="flex">
            <label>
                <input required placeholder="" type="text" class="input" name="username">
                <span>Username</span>
            </label>
  <div class="radio-container">

    <div class="radio-wrapper">
        <label class="radio-button">
        <input type="radio" name="plan" id="option1" value="1">
        <span class="radio-checkmark"></span>
        <span class="radio-label">Cut</span>
        </label>
    </div>

    <div class="radio-wrapper">
        <label class="radio-button">
        <input type="radio" name="plan" id="option2" value="2">
        <span class="radio-checkmark"></span>
        <span class="radio-label">Bulk</span>
        </label>
  </div>
    </div>
  <div class="radio-container">
    <div class="radio-wrapper">
        <label class="radio-button">
        <input type="radio" name="sex" id="option1" value="male">
        <span class="radio-checkmark"></span>
        <span class="radio-label">Male</span>
        </label>
    </div>

    <div class="radio-wrapper">
        <label class="radio-button">
        <input type="radio" name="sex" id="option2" value="female">
        <span class="radio-checkmark"></span>
        <span class="radio-label">Female</span>
        </label>
    </div>
</div>
</div>
    </div>
            <label>
                <input required placeholder="" type="email" class="input" name="email">
                <span>Email</span>
            </label>
    

    </label> 
            
    
        
    <label>
        <input required placeholder="" type="password" class="input" name="password" id="password">
        <span>Password</span>
        <span id="passwordPolicyMessage"></span>
    </label>
    <label>
        <input required placeholder="" type="password" class="input" name="confirm_password">
        <span>Confirm Password</span>
    </label>


    <div class="flex">
        <label>
        <input required placeholder="" type="number" class="input" name="birthday">
        <span>Age</span>
    </label> 
    <label>
        
        <input required placeholder="" type="number" class="input" name="height">
        <span>Height</span>
    </label> 
    <label>
        <input required placeholder="" type="number" class="input" name="weight">
        <span>Weight</span>
    </label> 
    </div>

    
    <button class="submit">Submit</button>
    <p class="signin">Already have an acount ? <a href="login.php">Login</a> </p>
</form>    
<script>
    const passwordInput = document.getElementById('password');
    const passwordPolicyMessage = document.getElementById('passwordPolicyMessage');

    passwordInput.addEventListener('input', () => {
        const password = passwordInput.value;
        let message = '';

        // Check password policy requirements
        if (password.length < 8) {
            message = 'Password must be at least 8 characters long.';
        } else if (!/\d/.test(password)) {
            message = 'Password must contain at least one digit (0-9).';
        } else if (!/[a-z]/.test(password) || !/[A-Z]/.test(password)) {
            message = 'Password must include both uppercase and lowercase letters.';
        } else if (!/[\W_]/.test(password)) {
            message = 'Password must include at least one special character (e.g., !, @, #).';
        }

        passwordPolicyMessage.textContent = message;
    });
</script>
</body>

</html>
