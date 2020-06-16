<?php
class User{
    private $con, $sqlData;
    public function __construct($con,$username)
    {
        $this->con = $con;
        $query = $this->con->prepare("SELECT * From users WHERE username=:un");
        $query->bindParam(':un',$username);
        $query->execute();
        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }
    public static function isLoggedIn(){
        return isset($_SESSION['userLoggedIn']);
    }
    public function getUsername(){
        return $this->sqlData['username'];
    }
    public function getName(){
        return $this->sqlData['first_name'].' '.$this->sqlData['last_name'];
    }
    public function getFirstName(){
        return $this->sqlData['first_name'];
    }
    public function getLastName(){
        return $this->sqlData['last_name'];
    }
    public function getEmail(){
        return $this->sqlData['email'];
    }
    public function getProfilePic(){
        return $this->sqlData['avatar_path'];
    }
    public function isSubscribedTo($userTo){
        $query = $this->con->prepare("SELECT * from subscribers where user_to=:userTo AND user_from=:userFrom");
        $query->bindParam(":userTo",$userTo);
        $username = $this->getUsername();
        $query->bindParam(":userFrom",$username);
        $query->execute();
        return $query->rowCount() > 0;
    }
    public function getSubscriberCount($userTo){
        $query = $this->con->prepare("SELECT * from subscribers where user_to=:userTo");
        $query->bindParam(":userTo",$userTo);
        $query->execute();
        return $query->rowCount();
    }
}
?>