<?php


class showAllVideo
{
    private $con,$video,$categoryList,$categorydb,$categoryFilter,$thumbnail,$categoryFilterquery;
    private $allVideoPath =array();
    public function __construct($con)
    {
        $this->con = $con;
        $query = $this->con->prepare("SELECT * From videos");
        $query->execute();
        $this->video = $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(){
        foreach ($this->video as $key => $value) {
            $filePath = $value["file_path"];
            $title = $value["title"];
            $uploaded_by = $value["uploaded_by"];
            $views = $value["views"];
            $upload_date = $value["upload_date"];
//            $upload_date = date('Y-m-d H:i:s', $value["upload_date"]);
            $videoid = $value["id"];
            $thumbnailpath = $this->getthumbnail($videoid);
            $thumbnailpath = $thumbnailpath["file_path"];
            $videolink = "<a href='watch.php?id=$videoid'><img src='$thumbnailpath' alt='$title' height='200' width='300'></a>";


            array_push($this->allVideoPath, "<div>$videolink
                    <br>
                    <span id='videoTitle'>$title</span><br>$uploaded_by<br>$views views &emsp;&emsp;&emsp;&emsp;&emsp;&emsp; $upload_date
                    </div> &emsp;&emsp;&emsp;");
        }
        return $this->allVideoPath;
    }

    private function getthumbnail($videoid){
        $query = $this->con->prepare("SELECT file_path from thumbnails where video_id =:video_id and selected=1");
        $query->bindParam(':video_id',$videoid);
        $query->execute();
        return $this->thumbnail = $query->fetch(PDO::FETCH_ASSOC);

    }

    public function categoryFilter($category){
        if(!strcmp($category,'All')){
            return   header("Location: index.php");

        }

        $query = $this->con->prepare("SELECT videos.* From videos inner join category on videos.category = category.id where category.name=:category");
        $query->bindParam(':category',$category);
        $query->execute();
        $this->categoryFilterquery = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($this->categoryFilterquery as $key => $value) {
            $filePath = $value["file_path"];
            $title = $value["title"];
            $uploaded_by = $value["uploaded_by"];
            $views =$value["views"];
            $upload_date = $value["upload_date"];
//            $upload_date = date('Y-m-d H:i:s',$value["upload_date"]);
            $videoid = $value["id"];
            $thumbnailpath = $this->getthumbnail($videoid);
            $thumbnailpath = $thumbnailpath["file_path"];
            $videolink = "<a href='watch.php?id=$videoid'><img src='$thumbnailpath' alt='$title' height='200' width='300'></a>";


            $this->categoryFilter .= "<div>$videolink
                    <br>
                    <span id='videoTitle'>$title</span><br>$uploaded_by<br>$views views &emsp;&emsp;&emsp;&emsp;&emsp;&emsp; $upload_date
                    </div> &emsp;&emsp;&emsp;";
        }
        return $this->categoryFilter;
    }
    public function getCategoryList()
    {
        $query = $this->con->prepare("SELECT distinct category.* From videos inner join category on videos.category = category.id");
        $query->execute();
        $this->categorydb = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($this->categorydb as $key => $value) {
            $category = $value["name"];

            $this->categoryList .= "
             <a class='dropdown-item' href='#'>$category</a>
            ";
        }
        return $this->categoryList;
    }
}

?>