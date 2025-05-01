<?php
session_start();
define('SITE_LOADED', true);
require_once 'models/User.php';
require_once 'includes/Validator.php';

$user = new User();
$validator = new Validator();

$errors = [];
$usernameError = '';
$emailError = '';
$passwordError = '';
$generalError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user->username = $_POST['username'];
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];
    
    if ($user->emailExists($user->email)) {
        $emailError = "Email already exists!";
    }
    else if ($user->password !== $_POST['confirm']) {
        $passwordError = "Passwords do not match!";
    }
    else {
        if ($user->create()) {
            header("Location: login.php");
            exit();
        }
        else {
            $generalError = "Registration failed!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="./css/register.css">
    <title>Book Spectrum - Register</title>
</head>

<body>
    <div class="container">
        <h2>Register</h2>
        <form action="" method="POST">
            <input type="text" name="username" id="username" placeholder="Username" 
                   class="<?php echo $usernameError ? 'error' : ''; ?>"
                   required>
            <?php if ($usernameError): ?>
                <small class="field-error"><?php echo htmlspecialchars($usernameError); ?></small>
            <?php endif; ?>
            
            <input type="email" name="email" id="email" placeholder="Email Address" 
                   class="<?php echo $emailError ? 'error' : ''; ?>"
                   required>
            <?php if ($emailError): ?>
                <small class="field-error"><?php echo htmlspecialchars($emailError); ?></small>
            <?php endif; ?>
            
            <input type="password" name="password" id="password" placeholder="Password" 
                   class="<?php echo $passwordError ? 'error' : ''; ?>"
                   required>
                   
            <input type="password" name="confirm" id="confirm" placeholder="Confirm Password" 
                   class="<?php echo $passwordError ? 'error' : ''; ?>"
                   required>
            <?php if ($passwordError): ?>
                <small class="field-error"><?php echo htmlspecialchars($passwordError); ?></small>
            <?php endif; ?>
            
            <input type="submit" value="Register" id="register">
            
            <?php if ($generalError): ?>
                <small class="field-error login-error"><?php echo htmlspecialchars($generalError); ?></small>
            <?php endif; ?>
        </form>
        
        <p>Already Have An Account ? <a href="./login.php">Login</a></p>
    </div>

    <script src="./js/register.js"></script>
</body>

</html>