<?php
class Video
{
    private $con,$sqlData,$userLoggedInObj;
    public function __construct($con,$input,$userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
        if(is_array($input)){
            $this->sqlData = $input;
        }
        else{
            $query = $this->con->prepare("SELECT * From videos WHERE id =:id");
            $query->bindParam(':id',$input);
            $query->execute();
            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }
    public function getId(){
        return $this->sqlData["id"];
    }
    public function getUploadedBy(){
        return $this->sqlData["uploaded_by"];
    }
    public function getTitle(){
        return $this->sqlData["title"];
    }
    public function getDescription(){
        return $this->sqlData["description"];
    }
    public function getPrivacy(){
        return $this->sqlData["privacy"];
    }
    public function getDuration(){
        return $this->sqlData["duration"];
    }
    public function getFilePath(){
        return $this->sqlData["file_path"];
    }
    public function getCategory(){
        return $this->sqlData["category"];
    }
    public function getUploadDate(){
        return $this->sqlData["upload_date"];
    }
    public function getViews(){
        return $this->sqlData["views"];
    }
    public function getLikes(){
        return 5;
    }
    public function incrementViews(){
        $query = $this->con->prepare("UPDATE videos SET views = views + 1 WHERE id =:id");
        $videoId = $this->getId();
        $query->bindParam(':id',$videoId);
        $query->execute();
        //讓資料庫和頁面顯示數量同步
        $this->sqlData['views'] += 1;
    }
}

?>