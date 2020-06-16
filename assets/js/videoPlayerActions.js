function likeVideo(button, videoId){
    $.post("ajax/likeVideo.php",{videoId:videoId}).
        done(function(data){
            var likeButton = $(button);
            var disLikeButton = $(button).siblings(".dislike-button");
            likeButton.addClass("active");
            disLikeButton.remove("active");
            var result = JSON.parse(data);
            console.log(result);
            updateLikesValue(likeButton.find('.text'),result.likes );
            updateLikesValue(disLikeButton.find('.text'),result.dislikes );
            if(result.likes < 0){
                likeButton.removeClass("active");
                likeButton.find("img:first").attr("src","assets/imgs/thumbs-up-white.png");
            }
            else{
                likeButton.find("img:first").attr("src","assets/imgs/thumbs-up-black.png");
            }
            disLikeButton.find("img:first").attr("src","assets/imgs/thumbs-down-white.png");
    });

}
function dislikeVideo(button, videoId){
    $.post("ajax/dislikeVideo.php",{videoId:videoId}).
    done(function(data){
        var dislikeButton = $(button);
        var LikeButton = $(button).siblings(".like-button");
        dislikeButton.addClass("active");
        LikeButton.remove("active");
        var result = JSON.parse(data);
        console.log(result);
        updateLikesValue(LikeButton.find('.text'),result.likes );
        updateLikesValue(dislikeButton.find('.text'),result.dislikes );
        if(result.dislikes < 0){
            dislikeButton.removeClass("active");
            dislikeButton.find("img:first").attr("src","assets/imgs/thumbs-down-white.png");
        }
        else{
            dislikeButton.find("img:first").attr("src","assets/imgs/thumbs-down-black.png");
        }
        LikeButton.find("img:first").attr("src","assets/imgs/thumbs-up-white.png");
    });

}
function updateLikesValue(el, num){
    var likesCountVal = el.text() || 0;
    el.text(parseInt(likesCountVal)+parseInt(num));
}