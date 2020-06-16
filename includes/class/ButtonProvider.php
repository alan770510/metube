<?php


class ButtonProvider
{
    public static $signInFunction = "notSignedIn()";
    public static function creatLink($link){
        return User::isLoggedIn() ? $link : ButtonProvider::$signInFunction;
}
    public static function createButton($text, $imgSrc = null, $action, $class, $icon =null,$color){
        $imgSrc = ($imgSrc == null) ? "" : "<img src='$imgSrc'>";
//        $icon = ($icon == null) ? "" : "<i class='iconfont icon-$icon'></i>";
        $action = ButtonProvider::creatLink($action);
        if (!strcmp($icon,"great")){
            $icon = ($icon == null) ? "" : "<img src = 'assets/imgs/thumbs-up-".$color.".png' width=\"30\" height=\"30\">";
        }
        else{
            $icon = ($icon == null) ? "" : "<img src = 'assets/imgs/thumbs-down-white.png' width=\"30\" height=\"30\">";
        }
        return "
                <button class='$class' onclick='$action'>
                    $imgSrc
                    $icon
                    <span class='text'>
                        $text
                    </span>
                </button>
        ";
    }
    public static function createHyperLinkButton($text, $imgSrc = null, $href, $class, $icon =null){
        $imgSrc = ($imgSrc == null) ? "" : "<img src='$imgSrc'>";
        $icon = ($icon == null) ? "" : "<i class='iconfont icon-$icon'></i>";

        return "
<a href ='$href'>
                <button class='$class'>
                    $imgSrc
                    $icon
                    <span class='text'>
                        $text
                    </span>
                </button>
                </a>
        ";
    }


    public static function createUserProfileButton($con,$username){
        $userObj = new User($con,$username);
        $profilePic = $userObj->getProfilePic();
        $link = "profile.php?username=$username";
        return "<a href='$link'>
<img src = '$profilePic' class = 'profile-pic'/>

</a>";
    }

    public static function createEditVideoButton($videoId){
        $href = "editVideo.php?videoId=$videoId";
        $button = ButtonProvider::createHyperLinkButton('Edit Video',null,$href,'edit-button');
        return "
                <div class='edit-video-button-container'>
                $button
</div>
        ";
    }
}