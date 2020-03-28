<?php
require_once('./includes/classes/channelProcessor.php');
require_once('./includes/config.php');
$usernameLoggedIn = isset($_SESSION['userLoggedIn']) ? $_SESSION['userLoggedIn'] : "";

if(isset($_POST['pagefunction'])){
    $channel = new channelProcessor($con,$usernameLoggedIn);
    echo json_encode($channel->create());
}

?>