<?php
require_once('./includes/config.php');
require_once('./includes/classes/FormSanitizer.php');
require_once('./includes/classes/Account.php');
require_once('./includes/classes/Constants.php');

$account = new Account($con);
if(isset($_POST['submit_button'])){
    $username = FormSanitizer::sanitizeUsername($_POST['Username']);
    $password = FormSanitizer::sanitizePassword($_POST['password']);
    $wasSuccessful = $account->login($username,$password);
    if($wasSuccessful){
        $_SESSION['userLoggedIn'] = $username;
        header('Location:index.php');
    }
    else{
        echo 'failed';
    }
}
function getInputValue($name){
    if(isset($_POST[$name])){
        echo $_POST[$name];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <!-- bootstrap css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <!-- my css -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <!-- iconfont css -->
    <link rel="stylesheet" href="./assets/iconfont/iconfont.css">
    <!-- my js-->
    <script src="./assets/js/common-action.js"></script>
</head>
<body>
<div class="sign-up-container">
    <div class="column">
        <div class="header">
            <img src="assets/imgs/Youtube.png" alt="MeTube">
            <h3>Login</h3>
            <span>Welcome to Login in MeTube</span>

        </div>
        <div class="sign-up-form">
            <form action="signIn.php" method="POST">
                <?php echo $account->getError(Constants::$loginFailed);?>
                <input type="text" placeholder="User name" name="Username" autocomplete="off" value="<?php getInputValue('Username');?>" require>
                <input type="password" placeholder="password" name="password" autocomplete="off" require>
                <input type="submit" name="submit_button" value="Login">
            </form>

        </div>
        <a class="sign-in-message" href="signUp.php">Don't have account? Click here to Sign up</a>
    </div>
</div>
</body>
</html>