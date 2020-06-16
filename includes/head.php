<?php
//require_once('./includes/clemsonconfig.php');
require_once('./includes/config.php');
require_once('./includes/class/User.php');
require_once('./includes/class/Video.php');
$usernameLoggedIn = isset($_SESSION['userLoggedIn']) ? $_SESSION['userLoggedIn'] : "";
$userLoggedInObj = new User($con,$usernameLoggedIn);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metube</title>
    <!--   jquery -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>

     <!-- Ajax-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- bootstrap css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- bootstrap.com JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"></script><!--        w3schools bootstrap tabs  JS -->
    <!-- bootstrap.com JS -->


    <!-- W3CSchool bootstrap nav tabs CSS   These two are conflict-->
    <!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">-->
    <!--    bootstrap.com JS -->
    <!--    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>-->
    <!--    These two are conflict-->



    <!--分頁外掛 pagination-->
    <script src="../assets/js/jquery.twbsPagination.js" type="text/javascript"></script>
    <!-- my css -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <!-- iconfont css -->
    <link rel="stylesheet" href="./assets/iconfont/iconfont.css">
    <!-- my js-->
    <script src="./assets/js/common-action.js"></script>
</head>
<body>
<div id ="page-container">

<div id="master-head-container">
       <button class="nav-show-hide"><i class="iconfont icon-guide"></i></button>
       <a href="./index.php" class="logo-container"><img src="./assets/imgs/Metube.png" alt="youtube"> </a>
       <div id="search-container">
           <form action="/search.php">
           <input type="text" class="search-bar" placeholder="search">
           <button><i class="iconfont icon-search"></i></button>
           </form>
       </div>
       <div class="right-icons">
           <a href="/upload.php"><i class="iconfont icon-upload"></i></a>
           <a href="/signIn.php"><i class="iconfont icon-user"></i></a>
       </div>
    </div>
    <div id ="side-nav-container" style="display:none;">
   
    </div>

    <div id="main-section-container" class="paddingleft" >
        <div id ="main-content-container" >