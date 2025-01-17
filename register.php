// register.php
@include 'config.php';

if (isset($_POST['submit'])) {
    $registration = new Registration($conn);

    $message = $registration->register($_POST['name'], $_POST['email'], $_POST['pass'], $_POST['cpass']);

    if ($message === 'registered successfully!') {
        header('Location: login.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Flora - Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" type="image/x-icon" href="images/icon.png">
    <link rel="stylesheet" href="css/admin_style.css">
    <style>
        body {
            background-image: url(login_background.png);
            background-size: cover;
        }
    </style>
</head>
<body>

<?php
if (isset($message)) {
    echo '
    <div class="message">
        <span>'.$message.'</span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>
    ';
}
?>

<section class="form-container">
    <form action="" method="post">
        <h3>register now</h3>
        <input type="text" name="name" class="box" placeholder="enter your username" required>
        <input type="email" name="email" class="box" placeholder="enter your email" required>
        <input type="password" name="pass" class="box" placeholder="enter your password" required>
        <input type="password" name="cpass" class="box" placeholder="confirm your password" required>
        <input type="submit" class="btn" name="submit" value="register now">
        <p>already have an account? <a href="login.php">login now</a></p>
    </form>
</section>

</body>
</html>
