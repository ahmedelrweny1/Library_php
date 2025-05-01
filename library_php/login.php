<?php
session_start();
define('SITE_LOADED', true);
require_once 'models/User.php';
require_once 'includes/Validator.php';

$user = new User();
$validator = new Validator();

$errors = [];
$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    $errors = $validator->validateLogin($email, $password);

    if (empty($errors))
    {
        $result = $user->login($email, $password);
        
        if ($result)
        {
            $_SESSION['user_id'] = $result['id'];
            $_SESSION['username'] = $result['username'];

            header("Location: index.php");
            exit();   
        } 
        else 
            $loginError = "Invalid email or password!";   
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="./css/login.css">
    <title>Book Spectrum - Login</title>
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        
        <form action="" method="POST">
            <input type="email" name="email" id="email"  placeholder="Email Address" 
                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                   class="<?php echo isset($errors['email']) ? 'error' : ''; ?>" 
                   required>
            <?php if (isset($errors['email'])): ?>
                <small class="field-error"><?php echo htmlspecialchars($errors['email']); ?></small>
            <?php endif; ?>
            
            <input type="password" name="password" id="password" placeholder="Password" 
                   class="<?php echo isset($errors['password']) ? 'error' : ''; ?>"
                   required>
            <?php if (isset($errors['password'])): ?>
                <small class="field-error"><?php echo htmlspecialchars($errors['password']); ?></small>
            <?php endif; ?>
    
            <input type="submit" value="Login" id="login">
            
            <?php if ($loginError): ?>
                <small class="field-error login-error"><?php echo htmlspecialchars($loginError); ?></small>
            <?php endif; ?>
        </form>
        
        <p>Don't Have An Account ? <a href="./register.php">Register </a></p>
    </div>
    <script src="./js/login.js"></script>
</body>

</html>