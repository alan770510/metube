<?php
require_once('./includes/classes/channelProcessor.php');
require_once('./includes/config.php');
$usernameLoggedIn = isset($_SESSION['userLoggedIn']) ? $_SESSION['userLoggedIn'] : "";

if(isset($_POST['pagefunction'])){
    $channel = new channelProcessor($con,$_POST['user'],$usernameLoggedIn);
    if(!strcmp($usernameLoggedIn,$_POST['user'])) {
        echo json_encode($channel->createSignIn());
    }
    else{
        echo json_encode($channel->create());
    }
}
if(isset($_POST['subscribe'])){
    $channel = new channelProcessor($con,$_POST['user'],$usernameLoggedIn);
    if($_POST['button']==1){
      echo $channel->addsubscribe();

    }
    else{
        echo $channel->unsubscribe();
    }
}

if(isset($_POST['mysubscribe'])){
    $channel = new channelProcessor($con,$_POST['user'],$usernameLoggedIn);
    echo json_encode($channel->createMySubscriptions());

}

if (isset($_POST['Delete'])) {
    if(isset($_POST['videoList'])) {
//    var_dump($_POST['contactList']);
        $channel = new channelProcessor($con,$_GET['channel'],$usernameLoggedIn);
        $channel->deleteVideo($_POST['videoList']);
        $reroute = 'channel.php?channel='.$_GET['channel'];
    header("Location:$reroute");
    }}
?>