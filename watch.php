<?php
    require_once('./includes/head.php');
    require_once('./includes/classes/VideoPlayer.php');
    require_once('./includes/classes/VideoinfoSection.php');

        if(!isset($_GET['id'])){
            echo 'illegal Video id';
            die;
        }
//        print_r($userLoggedInObj);
    $video = new Video($con, $_GET['id'],$userLoggedInObj);
//    echo $video->getTitle()."--";
//    echo $video->getViews();
    $video->incrementViews();



?>
 <div class="watch-left-column">
    <?php
    $videoPlayer = new VideoPlayer($video);
    echo $videoPlayer->create(false);
    $videoInfo = new VideoinfoSection($con,$video,$userLoggedInObj);
    echo $videoInfo->create();
    ?>
 </div>
<div class = "suggestions">

</div>





</div>
</div>
</div>
</body>
</html>