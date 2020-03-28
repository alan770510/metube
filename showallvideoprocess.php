<?php
require_once('./includes/classes/showAllVideo.php');
require_once('./includes/config.php');


if(isset($_POST['showallvideo'])){
    $allvideo = new showAllVideo($con);
    echo json_encode($allvideo->create());
}

?>