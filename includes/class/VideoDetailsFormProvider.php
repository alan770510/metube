<?php
class VideoDetailsFormProvider {
    private $con;
    public function __construct($con){
        $this->con = $con;
    }
     public function createUploadForm(){
         $fileInput = $this->createFileInput();
         $titleInput = $this->createTitleInput();
         $descriptionInput = $this->createDescriptionInput();
         $privacyInput = $this->createPrivacyInput();
         $categoriesInput = $this->createCategroiesInput();
         $uploadButton = $this->createUploadButton();
         //https://www.w3schools.com/tags/att_form_enctype.asp
         $processingPath = "../../processing.php";
//         $processingPath = "processing.php";
         return "
        <form action='$processingPath' method='POST' enctype='multipart/form-data'>
            $fileInput
            $titleInput
            $descriptionInput 
            $privacyInput
            $categoriesInput 
            $uploadButton 
        </form>
        ";
    }
    private function createFileInput(){
        return "
         <div class='form-group'>
            
            <input type='file' class='form-control-file' name='fileInput' required>
         </div>
        ";
    }

    private function createTitleInput(){
        return "
         <div class='form-group'>
            
            <input type='text' class='form-control' placeholder='Title' name='titleInput' required>
         </div>
        ";
    }

    private function createDescriptionInput(){
        return "
         <div class='form-group'>
            
            <textarea class='form-control' placeholder='Description' name='descriptionInput' row='3'></textarea>
         </div>
        ";
    }
    private function createPrivacyInput(){
        return"
        <div class='form-group'>
            <select name='privacy' class='form-control'>
                 <option value ='1'>Public</option>
                 <option value ='0'>Private</option>
            </select>
        </div>
        ";
    }

    private function createCategroiesInput(){
//              DB query
        $query = $this->con->prepare("select * from category");
        $query -> execute();
        $html = "<div class='form-group'> <select name='category' class='form-control'>";
        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $name = $row['name'];
            $id = $row['id'];
            $html .= " <option value ='$id'>$name</option>";
        }
        $html .="</select></div>";
        return $html;
    }
    private function createUploadButton(){
        return "<button type ='submit' class='btn btn-primary' name ='uploadButton'>Upload</button>";
    }
}
?>