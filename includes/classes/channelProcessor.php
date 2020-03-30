<?php



class channelProcessor
{
    private $con,$video,$thumbnail,$user,$usernameLoggedIn,$subscribe,$mysubscription,$uservideo;
    private $allVideoPath =array();
    private $subscribePath =array();
    private $signinallVideoPath=array();

    public function __construct($con,$user,$usernameLoggedIn)
    {
        $this->user=$user;
        $this->con = $con;
        $this->usernameLoggedIn =$usernameLoggedIn;
        $query = $this->con->prepare("SELECT * From videos where uploaded_by=:uploaded_by");
        $query->bindParam(':uploaded_by',$user);
        $query->execute();
        $this->video = $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(){
        foreach ($this->video as $key => $value) {

            $title = $value["title"];
            $uploaded_by = $value["uploaded_by"];
            $views =$value["views"];
            $upload_date = date('Y-m-d H:i:s',$value["upload_date"]);
            $videoid = $value["id"];
            $thumbnailpath = $this->getthumbnail($videoid);
            $thumbnailpath = $thumbnailpath["file_path"];
            $videolink = "<a href='watch.php?id=$videoid'><img src='$thumbnailpath' alt='$title' height='200' width='300'></a><br>";


            array_push($this->allVideoPath,"<div>$videolink
                   <span id='videoTitle'>$title</span><br>$uploaded_by<br>$views views &emsp;&emsp;&emsp;&emsp;&emsp;&emsp; $upload_date
                    </div> &emsp;&emsp;&emsp;");
        }
        return $this->allVideoPath;
    }

    private function getthumbnail($videoid){
        $query = $this->con->prepare("SELECT file_path From thumbnail where video_id =:video_id and selected=1");
        $query->bindParam(':video_id',$videoid);
        $query->execute();
        return $this->thumbnail = $query->fetch(PDO::FETCH_ASSOC);

    }
    private function getallthumbnail($videoid){
        $query = $this->con->prepare("SELECT file_path From thumbnail where video_id =:video_id ");
        $query->bindParam(':video_id',$videoid);
        $query->execute();
        return $this->thumbnail = $query->fetchAll(PDO::FETCH_ASSOC);

    }
    public function createSignIn(){
        foreach ($this->video as $key => $value) {

            $title = $value["title"];
            $uploaded_by = $value["uploaded_by"];
            $views =$value["views"];
            $upload_date = date('Y-m-d H:i:s',$value["upload_date"]);
            $videoid = $value["id"];
            $thumbnailpath = $this->getthumbnail($videoid);
            $thumbnailpath = $thumbnailpath["file_path"];
            $videolink = "<a href='watch.php?id=$videoid'><img src='$thumbnailpath' alt='$title' height='200' width='300'></a><br>";


            array_push($this->signinallVideoPath,"<input type=\"checkbox\" name=\"videoList[]\" value = \"$videoid\">
                    <div>$videolink<span id='videoTitle'>$title</span><br>$uploaded_by<br>$views views &emsp;&emsp;&emsp;&emsp;&emsp;&emsp; $upload_date
                    </div> &emsp;&emsp;&emsp;");
        }

        return $this->signinallVideoPath;
    }
    public function createMySubscriptions(){
        $query = $this->con->prepare("SELECT * From subscriptions where username=:mainuser");
        $query->bindParam(':mainuser',$this->usernameLoggedIn);
        $query->execute();
        $this->mysubscription = $query->fetchAll(PDO::FETCH_ASSOC);



        foreach ($this->mysubscription as $key => $value) {
            $username = ucfirst($value["Subscriptions"]);
//            依據subscribe找出他的影片
            $query = $this->con->prepare("SELECT * From videos where uploaded_by=:uploaded_by");
            $query->bindParam(':uploaded_by',$value["Subscriptions"]);
            $query->execute();
            $this->uservideo = $query->fetchAll(PDO::FETCH_ASSOC);
            $subscribeVideoPath ="";
            $count = 1;
            foreach ($this->uservideo as  $value) {
                $title = $value["title"];
                $uploaded_by = $value["uploaded_by"];
                $views = $value["views"];
                $upload_date = date('Y-m-d H:i:s', $value["upload_date"]);
                $videoid = $value["id"];
                $thumbnailpath = $this->getthumbnail($videoid);
                $thumbnailpath = $thumbnailpath["file_path"];
                $videolink = "<a href='watch.php?id=$videoid'><img src='$thumbnailpath' alt='$title' height='100' width='200'></a><br>";
                $subscribeVideoPath .= "
                    <div >$videolink
                   <span id='videoTitle'>$title</span><br>$uploaded_by<br>$views views &emsp; $upload_date
                    </div> &emsp;&emsp;&emsp;";
                $count++;
//                每個subscribe channel 只顯示四筆
                if($count > 4) {break;}
            }
            array_push($this->subscribePath,"<a href=\"channel.php?channel=$username\">$username's Channel</a> <div class='video'> $subscribeVideoPath </div>
            ");
        }
        return $this->subscribePath;

    }

    public function addsubscribe(){

//        沒登入不能subscribe 退回登入頁面
        if(empty($this->usernameLoggedIn)){
//            return "alert('You are not login, redirect to Login page after click'); location.href = 'signIn.php';";
            return "You are not login, redirect to Login page after click";

        }
 /*       if(!strcmp($this->usernameLoggedIn,$this->user)) {
            return "You cannot subscribe yourself";
        }*/
        if(!$this->checksubscribe())
        {
//沒有重複 插入資料表
        $query = $this->con->prepare("INSERT INTO subscriptions (username,Subscriptions) value(:mainuser,:subscriptions)");
        $query->bindParam(':mainuser', $this->usernameLoggedIn);
        $query->bindParam(':subscriptions',$this->user);
        $query->execute();

//            return 'alert("Subscribe successful")';

        return 'Subscribe Successful';
        }
        else{
            return 'You already subscribed';
        }
    }
    public function unsubscribe(){

//        沒登入不能unsubscribe 退回登入頁面
        if(empty($this->usernameLoggedIn)){
            return "You are not login, redirect to Login page after click";
        }

        if($this->checksubscribe())
        {

            $query = $this->con->prepare("DELETE FROM subscriptions WHERE username=:mainUser AND Subscriptions=:subscriptions");
            $query->bindParam(':mainUser', $this->usernameLoggedIn);
            $query->bindParam(':subscriptions',$this->user);
            $query->execute();
//            return 'alert("Unsubscribe successful");location.href = \'channel.php?channel='.$this->user.'\';';
            return 'Unsubscribe Successful';

        }
        else{
            return 'You already unsubscribed';
        }
    }

    public function checksubscribe(){
// 判斷已經重複 subscribe
        $query = $this->con->prepare("Select * from subscriptions where username=:mainuser and Subscriptions=:subscriptions");
        $query->bindParam(':mainuser', $this->usernameLoggedIn);
        $query->bindParam(':subscriptions',$this->user);
        $query->execute();
        $this->subscribe = $query->fetchAll(PDO::FETCH_ASSOC);
       return count($this->subscribe);
    }
    private function deleteFile($filePath)
    {
        if (!unlink($filePath)) {
            return false;
        }
        else{
            return true;
        }
    }
    public function deleteVideo($deleteList){
        foreach ($this->video as  $value) {
            $this->deleteFile($value["File_path"]);
            $videoid = $value["id"];
            $thumbnailpath = $this->getallthumbnail($videoid);
            foreach($thumbnailpath as $thumbialvalue){
                $thumbnailpath = $thumbialvalue["file_path"];
                $this->deleteFile($thumbnailpath);}

        }

        $qMarks = str_repeat('?,', count($deleteList) - 1) . '?';
        $mainUser = "'".$this->usernameLoggedIn."'";
        $query = $this->con->prepare("DELETE FROM videos WHERE uploaded_by= $mainUser AND id IN ($qMarks)");
        $query->execute($deleteList);
        $query = $this->con->prepare("DELETE FROM thumbnail WHERE  video_id IN ($qMarks)");
        $query->execute($deleteList);
    }
    private function checkPlayList($playlistname){
        $query = $this->con->prepare("SELECT * FROM playlist where mainuser=:mainuser and playlistname=:playlistname");
        $query->bindParam(':mainuser', $this->usernameLoggedIn);
        $query->bindParam(':playlistname',$playlistname);
        $query->execute();
        $dbresult = $query->fetchAll(PDO::FETCH_ASSOC);
        return count($dbresult);
    }
    public function createPlayList($playlistname){
        if(!empty($playlistname)){
        if(!$this->checkPlayList($playlistname)){

        $query = $this->con->prepare("INSERT INTO playlist (mainuser,playlistname) value(:mainuser,:playlistname)");
        $query->bindParam(':mainuser', $this->usernameLoggedIn);
        $query->bindParam(':playlistname',$playlistname);
        $query->execute();
        return "Create Playlist successful";
        }else{return "You cannot add duplicate playlist name";}

        }
        else{
            return "You can not add empty record";
        }

    }
    private function getVideoInfoViaPlayList($playlist){
        $query = $this->con->prepare("SELECT * FROM playlist where mainuser=:mainuser and playlistname=:playlistname");
        $query->bindParam(':mainuser', $this->usernameLoggedIn);
        $query->bindParam(':playlistname', $playlist);
        $query->execute();
        return  $query->fetchAll(PDO::FETCH_ASSOC);

    }
    public function showPlayList(){
        $query = $this->con->prepare("SELECT distinct playlistname FROM playlist where mainuser=:mainuser");
        $query->bindParam(':mainuser', $this->usernameLoggedIn);
        $query->execute();
        $dbresult = $query->fetchAll(PDO::FETCH_ASSOC);
        $allplaylist ='';
        foreach ($dbresult as  $value) {
            $allplaylist .= '<p>'.$value["playlistname"].'<p>';
            $allVideoid = $this->getVideoInfoViaPlayList($value["playlistname"]);
            $qMarks = str_repeat('?,', count($allVideoid) - 1) . '?';
            $allvideoidarray =array();
            foreach($allVideoid as $value){
               array_push($allvideoidarray,$value['video_id']);
            }
            $query = $this->con->prepare("select * FROM videos WHERE id IN ($qMarks)");
            $query->execute($allvideoidarray);
            $videoresult = $query->fetchAll(PDO::FETCH_ASSOC);
            $playlistVideoPath = '';
            $count =0;
            foreach ($videoresult as  $value) {
                $title = $value["title"];
                $uploaded_by = $value["uploaded_by"];
                $views = $value["views"];
                $upload_date = date('Y-m-d H:i:s', $value["upload_date"]);
                $videoid = $value["id"];
                $thumbnailpath = $this->getthumbnail($videoid);
                $thumbnailpath = $thumbnailpath["file_path"];
                $videolink = "<a href='watch.php?id=$videoid'><img src='$thumbnailpath' alt='$title' height='100' width='200'></a><br>";
                $playlistVideoPath .= "
                    <div >$videolink
                   <span id='videoTitle'>$title</span><br>$uploaded_by<br>$views views &emsp; $upload_date
                    </div> &emsp;&emsp;&emsp;";
                $count++;
//                每個subscribe channel 只顯示4筆
                if($count > 4) {break;}
            }
            $allplaylist .=  $playlistVideoPath;
        }
        return $allplaylist;
    }
     public function showchannelonly(){
        if(!$this->checksubscribe()){
            $button = "<div><button type=\"button\"  class=\"btn btn-danger\"  id='subscribe'>Subscribe</button> </div>";

        }
        else{
            $button = "<div><button type=\"button\"  class=\"btn btn-danger\"  id='unsubscribe'>Unsubscribe</button> </div>";
        }
        return "
          $button
        <ul class=\"nav nav-tabs \">
        <li class=\"active\"><a data-toggle=\"tab\" href=\"#Channel\">Channel</a></li>
       
    </ul>

    <div class=\"tab-content\">
        <div id=\"Channel\" class=\"tab-pane fade in active\">
            
            <div id=\"show\">
            </div>
           
            <div id=\"page-nav\">
                <nav aria-label=\"Page navigation\">
                    <ul class=\"pagination\" id=\"pagination\"></ul>
                </nav>
            </div>
        </div>
   
    </div>
        ";
    }
    public function showall(){
//        如果沒影片就不要出現刪除按鈕
        if(!count($this->video)){
            $deletebutton= "";
        }else{
            $deletebutton = " <input type=\"submit\" class=\"btn btn-danger\" id=\"delete\" name = \"Delete\" value =\"Delete\"><br>";
        }

//      create playlist button
        $createPlaylistButton = " <input type=\"button\" class=\"btn btn-outline-primary\" id=\"createPlayList\" name = \"createPlayList\" value =\"Create PlayList\"><br>";



        return "<ul class=\"nav nav-tabs \" id=\"myTab1\">
        <li id=\"channel1\" class=\"active\"><a data-toggle=\"tab\" href=\"#channel2\">Channel</a></li>
        <li><a data-toggle=\"tab\" href=\"#mySubscriptions\">My Subscriptions</a></li>
        <li id=\"myPlayList1\" ><a data-toggle=\"tab\" href=\"#myPlayList2\">My Playlist</a></li>
        <li><a data-toggle=\"tab\" href=\"#menu3\">Menu 3</a></li>
    </ul>

    <div class=\"tab-content\">
        <div id=\"channel2\" class=\"tab-pane fade in active\">
            <form action=\"channelprocess.php?channel=$this->user\" method=\"post\">
            $deletebutton
            <div id=\"show\">
            </div>
            </form>
            <div id=\"page-nav\">
                <nav aria-label=\"Page navigation\">
                    <ul class=\"pagination\" id=\"pagination\"></ul>
                </nav>
            </div>
        </div>
        <div id=\"mySubscriptions\" class=\"tab-pane fade\">
           <div id=\"showSubscriptions\"></div>
        </div>
        <div id=\"myPlayList2\" class=\"tab-pane fade\">
            $createPlaylistButton
            <div id=\"showMyPlayList\"></div>
        </div>
        <div id=\"menu3\" class=\"tab-pane fade\">
            <h3>Menu 3</h3>
            <p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
        </div>
    </div>";
    }
}