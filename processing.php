<?php
require_once('./includes/head.php');
require_once('./includes/class/videoUploadData.php');
require_once('./includes/class/videoProcessor.php');
    if(!isset($_POST['uploadButton'])){
        echo "No document upload!";
        exit();
    }
//    1 處理基本數據
$videoUploadData= new videoUploadData(
     $_FILES['fileInput'],
     $_POST['titleInput'],
     $_POST['descriptionInput'],
     $_POST['privacy'],
     $_POST['category'],
     $userLoggedInObj->getUsername()
);
//  2.上傳視頻文件
$videoProcessor = new videoProcessor($con);
$wasSuccessful = $videoProcessor->uploadVideo($videoUploadData);
// 3.判斷上傳狀態
?>
