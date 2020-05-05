function likeVideo(button, videoId){
    $.post("ajax/likeVideo.php",{videoId:videoId}).
        done(function(data){
            var likeButton = $(button);
            var disLikeButton = $(button).siblings(".dislike-button");
            likeButton.addClass("active");
            disLikeButton.remove("active");
            var result = JSON.parse(data);
            console.log(result);
    });

}