<?php
class videoUploadData{
    public $videoDataArray,$title,$description,$privacy,$category,$uploadBy;
    public function __construct($videoDataArray,$title,$description,$privacy,$category,$uploadBy)
    {
        $this->videoDataArray = $videoDataArray;
        $this->title =$title;
        $this->category =$category;
        $this->description =$description;
        $this->privacy =$privacy;
        $this->uploadBy =$uploadBy;
    }
}
?>