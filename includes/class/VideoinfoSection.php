<?php
require_once('./includes/class/VideoinfoControls.php');

class VideoinfoSection
{
    private $con, $video, $userLoggedInObj;

    public function __construct($con, $video, $userLoggedInObj)
    {
        $this->con = $con;
        $this->video = $video;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create()
    {
        return $this->getVideoInfo() . $this->getUserInfo();

    }

    private function getVideoInfo()
    {
        $title = $this->video->getTitle();
        $views = $this->video->getViews();
        $videoInfoControls = new VideoInfoControls($this->video, $this->userLoggedInObj);
        $conntrols = $videoInfoControls->create();
        return "
            <div class='video-info'>
                <h1>$title</h1>
                <div class='bottom-section'>
                    <span class='view-count'>$views</span>
                    $conntrols
                 </div>
              </div>
            ";
    }

    private function getUserInfo()
    {
        $description = $this->video->getDescription();
        $uploadDate = $this->video->getUploadDate();
        $uploadedBy = $this->video->getUploadedBy();
        $profileButton = ButtonProvider::createUserProfileButton($this->con, $uploadedBy);
        if($uploadedBy == $this->userLoggedInObj->getUserName()){
            $actionButton = ButtonProvider::createEditVideoButton($this->video->getId());
        }else{
            $actionButton = '';
        }
        return "<div class ='user-info'>
                <div class ='top-row'>
                $profileButton
                <div class ='upload-info'>
                <span class ='owner'>
                <a href='profile.php?username=$uploadedBy'>
                $uploadedBy
</a>
</span>
<span class='date'> Uploaded on: $uploadDate
</span>
</div>
$actionButton
                </div>
                </div>";
    }

}