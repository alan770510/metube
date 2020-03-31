<?php
//require_once('./includes/clemsonconfig.php');

require_once('./includes/config.php');
require_once('./includes/class/FormSanitizer.php');
require_once('./includes/class/Account.php');
require_once('./includes/class/Constants.php');
$account = new Account($con);
    if(isset($_POST['submit_button'])){
//        因為宣告static 只需要用:  不需要宣告新物件
        $firstName = FormSanitizer::sanitizeFormString($_POST['first_name']);
        $lastName = FormSanitizer::sanitizeFormString($_POST['last_name']);
        $username = FormSanitizer::sanitizeUsername($_POST['Username']);
        $email = FormSanitizer::sanitizeFormEmail($_POST['Email']);
        $connfirmEmail = FormSanitizer::sanitizeFormEmail($_POST['confirm_Email']);
        $password = FormSanitizer::sanitizePassword($_POST['password']);
        $connfirmPassword = FormSanitizer::sanitizePassword($_POST['confirm_password']);
        //註冊用戶
        $wasSuccessful = $account->register($firstName,$lastName,$username,$email,$connfirmEmail,$password,$connfirmPassword);
        if($wasSuccessful){
            //註冊成功後跳到主頁
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
                <img src="assets/imgs/Metube.png" alt="MeTube">
                <h3>Registration</h3>
                <span>Welcome to register MeTube</span>

            </div>
            <div class="sign-up-form">
                <form action="signUp.php" method="post">
                    <?php echo $account->getError(Constants::$FirstNameCharactor);?>
                    <input type="text" placeholder="First name" name="first_name" autocomplete="off" value="<?php getInputValue('first_name');?>" require>
                    <?php echo $account->getError(Constants::$LastNameCharactor);?>
                    <input type="text" placeholder="Last name" name="last_name" autocomplete="off" value="<?php getInputValue('last_name');?>" require>
                    <?php echo $account->getError(Constants::$usernameCharactor);?>
                    <?php echo $account->getError(Constants::$usernameTaken);?>
                    <input type="text" placeholder="User name" name="Username" autocomplete="off" value="<?php getInputValue('Username');?>" require>
                    <?php echo $account->getError(Constants::$EmailsDoNotMatch);?>
                    <?php echo $account->getError(Constants::$EmailsInvailed);?>
                    <?php echo $account->getError(Constants::$emailTaken);?>
                    <input type="email" placeholder="Email" name="Email" autocomplete="off" value="<?php getInputValue('Email');?>" require>
                    <input type="email" placeholder="Confirm Email" name="confirm_Email" autocomplete="off" value="<?php getInputValue('confirm_Email');?>" require>
                    <?php echo $account->getError(Constants::$passwordsDoNotMatch);?>
                    <?php echo $account->getError(Constants::$passwordsNotAlphaNumeric);?>
                    <?php echo $account->getError(Constants::$passwordLength);?>
                    <input type="password" placeholder="password" name="password" autocomplete="off" require>
                    <input type="password" placeholder="Confirm password" name="confirm_password" autocomplete="off" require>
                    <input type="submit" name="submit_button" value="Submit">
                </form>

            </div>
            <a class="sign-in-message" href="signIn.php">Already have account? Click here to login</a>
        </div>
    </div>
</body>
</html>