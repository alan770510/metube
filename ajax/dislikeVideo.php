<?php
require_once("../includes/config.php");
require_once("../includes/class/Video.php");
require_once("../includes/class/User.php");
$username = $_SESSION['userLoggedIn'];
$videoId =  $_POST['videoId'];
$userLoggedInObj = new User($con,$username);
$video = new Video($con,$videoId,$userLoggedInObj);
echo $video->dislike();
?>