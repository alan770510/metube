<?php
class VideoPlayer
{
    private $video;
    public function  __construct($video)
    {
        $this->video = $video;
    }
    public function create($autoPlay){
        $autoPlay = $autoPlay ? 'autoPlay' : "";
        $filePath = $this->video->getFilePath();
        return "<video class= 'video-player' controls $autoPlay>
        <source src='$filePath' type='video/MP4'> </source>
        </video>";
    }
}