<?php
class videoProcessor{
    private $con;
//    bytes 500mb
    private $sizeLimit = 500000000;
    private $videoTypes = array('avi','wmv','mpeg','mp4','rmvb','wmv','3gp','mkv','flv');
    private $ffmpegPath;
    private $ffprobePath;
    public function __construct($con)
    {
        $this->con =$con;

    /*   不知道為啥which 在linux上無法跑 只好直接指定
       $this->ffmpegPath = exec("which ffmpeg");
        $this->ffprobePath = exec("which ffprobe");
    */
//       $this->ffmpegPath = "/usr/bin/ffmpeg";
//        $this->ffprobePath = "/usr/bin/ffprobe";
                   //realpath 抓取絕對路徑
        $this->ffmpegPath = realpath("ffmpeg/bin/ffmpeg.exe");
        $this->ffprobePath = realpath("ffmpeg/bin/ffprobe.exe");
    }
    public function uploadVideo($videoUploadData)
    {

        $targetDir = 'uploads/videos/';
        $videoData = $videoUploadData->videoDataArray;
//        The basename() function returns the filename from a path.
        $tempFilePath = $targetDir . uniqid() . basename($videoData['name']);
//       拿掉空白 $tempFilePath = str_replace(" ","_",$tempFilePath);
        $tempFilePath = preg_replace('/\s+/', '_', $tempFilePath);
        $isValidData = $this -> processData($videoData, $tempFilePath);

        if(!$isValidData){
            return false;
        }
//        轉移視頻  echo $tempFilePath; temp path:  C:\wamp64\tmp\phpD1F8.tmp
        //move_uploaded_file內建函數
        if(move_uploaded_file($videoData['tmp_name'],$tempFilePath)){
            $finalFilePath = $targetDir.uniqid().'.mp4';

            if(!$this->insertVideoData($videoUploadData,$finalFilePath)){
                echo 'Video file input to Database failed';
                return false;
            }

            //測試視頻轉碼是否成功
            if(!$this->convertVideoToMp4($tempFilePath,$finalFilePath)){
                echo 'Convert video failed';
                return false;
            }
            //刪除原始視頻
            if(!$this->deleteFile($tempFilePath)){
                echo 'Delete original video file failed';
                return false;
            }
//            Must add before generate thumbnails function
            if(!$this->updateFileSize($finalFilePath)){
                echo 'Update file size to Database failed';
                return false;
            }
            //生成三張縮略圖
            if(!$this->generateThumbnails($finalFilePath)){
                echo 'Get video duration failed';
                return false;
            }

//            echo 'Generate Thumbnails successful';
            echo 'Video upload successful <br>';
            return true;
        }
    }
        private function processData($videoData,$FilePath){
//        print_r($videoData);
//        var_dump($videoData);
            $videoType = pathInfo($FilePath, PATHINFO_EXTENSION);
            if(!$this->isValidSize($videoData)){
                echo 'The video file size cannot exceed '.$this->sizeLimit.' bytes';
                return false;
            }
            elseif(!$this->isValidType($videoType)){
                echo 'Your uploaded file type is: '.$videoType.'<br>';
                echo 'document type invalid! <br>';
                return false;
            }
            elseif($this->hasError($videoData)){
                echo "upload video file failed";
                return false;
            }
            return true;
        }
        private function isValidSize($videoData){
            return $videoData['size'] <= $this->sizeLimit;
        }
        private function isValidType($videoType){
            $lowercased =strtolower($videoType);
            return in_array($lowercased, $this->videoTypes);
        }
        private function hasError($videoData){
            return $videoData['error'] !=0;
        }
//        input video to DB  bindParam: https://www.php.net/manual/en/pdostatement.bindparam.php
        private function insertVideoData($videoUploadData,$finalFilePath){
            $query =$this->con->prepare("INSERT INTO videos(uploaded_by,title, description,privacy,file_path,category,upload_date)
                                        VALUES(:uploaded_by, :title, :description, :privacy, :file_path, :category, :upload_date)");
//            $query->bindParam(':uploaded_by',$videoUploadData->uploadBy);
            $uploaded_by ='alan';
            $query->bindParam(':uploaded_by',$uploaded_by);
            $query->bindParam(':title',$videoUploadData->title);
            $query->bindParam(':description',$videoUploadData->description);
            $query->bindParam(':privacy',$videoUploadData->privacy);
            $query->bindParam(':file_path',$finalFilePath);
            $query->bindParam(':category',$videoUploadData->category);
//            $updateTime = time();
            $updateTime = date("Y-m-d H:i:s");
            $query->bindParam(':upload_date',$updateTime);
            return $query->execute();
        }
        //視頻格式轉換成MP4
        private function convertVideoToMp4($tempFilePath,$finalFilePath){
//      File descriptor 2 is the standard error,  & indicates that what follows is a file descriptor and not a file name
//            windows執行要用絕對路徑  linux 直接用/sbin/ffmpeg instead of $this->
            $cmd ="$this->ffmpegPath -i $tempFilePath $finalFilePath 2>&1";
            $outputLog = array();
//            https://www.php.net/manual/en/function.exec.php
            exec($cmd, $outputLog,$returnCode);
            if($returnCode!=0){
                foreach($outputLog as $line){
                    echo $line.'<br>';
                }
                return false;
            }
            return true;
        }
        //刪除原始視頻
        private function deleteFile($filePath){
            if(!unlink($filePath)){
                return false;
            }
            return true;
        }
        //生成縮略圖
        private function generateThumbnails($filepath){
            $thumbnailsize = "210x118";
            $numThumbnails =3;
            $pathToThumbnails = "uploads/videos/thumbnails";
//           抓取video duration
            $duration = $this->getVideoDuration($filepath);
            $videoId = $this->con->lastInsertId();
            $this->updateDuration($duration,$videoId);
            for($num = 1;$num <= $numThumbnails;$num++){
                $imageName = uniqid().'.jpg';
                $interval = ($duration * 0.8) / $numThumbnails * $num;
                $fullThumbnailPath = "$pathToThumbnails/$videoId-$imageName";
//                https://networking.ringofsaturn.com/Unix/extractthumbnail.php
                $cmd ="$this->ffmpegPath -i $filepath -ss $interval -s $thumbnailsize -vframes 1 $fullThumbnailPath 2>&1";
                $outputLog = array();
//            https://www.php.net/manual/en/function.exec.php
                exec($cmd, $outputLog,$returnCode);
                if($returnCode!=0){
                    foreach($outputLog as $line){
                        echo $line.'<br>';
                    }
                }
                $selected = $num == 1 ? 1 : 0;
                $query = $this->con->prepare("INSERT INTO thumbnails(video_id, file_path, selected)
                 VALUES(:video_id, :file_path, :selected)");
                $query->bindParam(':video_id',$videoId);
                $query->bindParam(':file_path',$fullThumbnailPath);
                $query->bindParam(':selected',$selected);
                $success = $query->execute();
                if(!$success){
                    echo 'thumbnails insert to DB failed';
                    return false;
                }
            }
            return true;
        }
        //獲取視頻時長
        private function getVideoDuration($filePath){
            return (int) shell_exec("$this->ffprobePath -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $filePath");
        }
        private function updateDuration($duration, $videoId){
            $hours = floor($duration / 3600);
            $mins = floor(($duration - ($hours * 3600)) / 60 );
            $secs = floor($duration % 60 );
            $hours = ($hours < 1) ? "" : $hours.':';
            $mins = ($mins < 10 ) ? "0".$mins.":" : $mins.":";
            $secs = ($secs < 10 ) ? "0".$secs : $secs;
            $duration= $hours.$mins.$secs;
            //修改資料庫紀錄
            $query = $this->con->prepare("UPDATE videos SET video_duration=:duration where id=:videoId");
            $query->bindParam(':duration',$duration);
            $query->bindParam(':videoId',$videoId);
            return $query->execute();
        }
    private function updateFileSize($finalFilePath){
        $finalFileSize = filesize($finalFilePath);
        echo 'filesize';
        print_r($finalFileSize);
        $videoId = $this->con->lastInsertId();
        echo 'video id';
        print_r($videoId);
        $query = $this->con->prepare("UPDATE videos SET file_size=:file_size where id=:videoId");
        $query->bindParam(':file_size',$finalFileSize);
        $query->bindParam(':videoId',$videoId);
        return $query->execute();
    }
}
