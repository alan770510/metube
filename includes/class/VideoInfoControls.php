<?php
require_once('./includes/class/ButtonProvider.php');

class VideoInfoControls
{
    private $video,$userLoggedInObj;

    public function  __construct($video,$userLoggedInObj)
    {
        $this->video = $video;
        $this->userLoggedInObj = $userLoggedInObj;
    }
    public function create(){
        $likeButton = $this->createLikeButton();
        $dislikeButton = $this->createDislikeButton();
        return "<div>
                $likeButton
                $dislikeButton
                </div>";

    }
    private function createLikeButton(){
        $text = $this->video->getLikes();
        $videoId = $this->video->getID();
        $action = "likeVideo(this,$videoId)";
        $class = 'like-button';
//        $imgSrc = 'assets/imgs/thumb-up.png';
        $icon = 'great';
        return ButtonProvider::createButton($text,'',$action,$class,$icon);
    }
    private function createDislikeButton(){
        $text = $this->video->getDislikes();
        $videoId = $this->video->getID();
        $action = "dislikeVideo(this,$videoId)";
        $class = 'dislike-button';
//        $imgSrc = 'assets/imgs/thumb-up.png';
        $icon = 'bad';
        return ButtonProvider::createButton($text,'',$action,$class,$icon);
    }

}