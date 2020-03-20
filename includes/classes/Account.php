<?php
    class Account{
        private $con;
        private $errorArray = [];
        public function __construct($con)
        {
            $this->con = $con;
        }
        //處理登入
        public function login($username,$password){
            $password = md5($password);
            $query = $this->con->prepare("SELECT * From users WHERE username=:un AND password = :pw");
            $query->bindParam(':un',$username);
            $query->bindParam(':pw',$password);
            $query->execute();
            if($query->rowCount() == 1){
                return true;
            }
            else{
                array_push($this->errorArray,Constants::$loginFailed);
                return false;
            }
        }


        //處理註冊
        public function register($fn, $ln, $un, $em, $cf_em, $pw, $cf_pw){
            $this->validateLastName($ln);
            $this->validateFirstName($fn);
            $this->validateUserName($un);
            $this->validateEmail($em,$cf_em);
            $this->validatePasswords($pw,$cf_pw);
            if(empty($this->errorArray)){
                return $this->insertUserDetails($fn,$ln,$un,$em,$pw);
            }
            else{
                return false;
            }
        }
        private function insertUserDetails($fn,$ln,$un,$em,$pw){
            $pic = 'assets/profilePictures/default.png';
//            $pw = hash('sha512',$pw);
            $pw = md5($pw);
            $signUpTime = time();
            $query = $this->con->prepare("INSERT INTO users (first_name,last_name, username, email, password, sign_up_time, profile_pic)
                                           VALUE(:fn, :ln, :un, :em, :pw,:sign_up, :pic)");
            $query->bindParam(':fn',$fn);
            $query->bindParam(':ln',$ln);
            $query->bindParam(':un',$un);
            $query->bindParam(':em',$em);
            $query->bindParam(':pw',$pw);
            $query->bindParam(':sign_up',$signUpTime);
            $query->bindParam(':pic',$pic);
            return $query->execute();

        }
        private function validateLastName($ln){
            if(strlen($ln) > 25 || strlen($ln) < 2){
                array_push($this->errorArray,Constants::$LastNameCharactor);
            }
        }
        private function validateFirstName($fn){
            if(strlen($fn) > 25 || strlen($fn) < 2){
                array_push($this->errorArray,Constants::$FirstNameCharactor);
            }
        }
        private function validateUserName($un){
            if(strlen($un) > 25 || strlen($un) < 3){
                array_push($this->errorArray,Constants::$usernameCharactor);
                return;
            }
            $query = $this->con->prepare("SELECT username From users WHERE username=:un");
            $query->bindParam(':un',$un);
            $query->execute();
            if($query->rowCount() != 0){
                array_push($this->errorArray,Constants::$usernameTaken);
            }
        }
        private function validateEmail($em,$cf_em){
            //驗證email是否match
            if($em != $cf_em){
                array_push($this->errorArray,Constants::$EmailsDoNotMatch);
                return;
            }
            //驗證email格式
            if(!filter_var($em,FILTER_VALIDATE_EMAIL)){
                array_push($this->errorArray,Constants::$EmailsInvailed);
                return;
            }
            //驗證email是否被註冊
            $query = $this->con->prepare("SELECT email From users WHERE email=:em");
            $query->bindParam(':em',$em);
            $query->execute();
            if($query->rowCount() != 0){
                array_push($this->errorArray,Constants::$emailTaken);
            }
        }

        private function validatePasswords($pw,$cf_pw){
            //驗證password是否match
            if($pw != $cf_pw){
                array_push($this->errorArray,Constants::$passwordsDoNotMatch);
                return;
            }
            //驗證email格式要有特殊字元  [^xyz] 比對不再中括弧出現的任一字元 https://medium.com/verybuy-dev/php%E6%AD%A3%E8%A6%8F%E8%A1%A8%E9%81%94%E5%BC%8F%E6%AF%94%E5%B0%8D-89b03ebc10eb
//             if(preg_match('/^(\w){6,30}$/',$pw)){
            if(!preg_match('/[^A-Za-z0-9]/',$pw)){
                array_push($this->errorArray,Constants::$passwordsNotAlphaNumeric);
                return;
            }
            //驗證密碼長度
            if(strlen($pw) > 30 || strlen($pw) < 6){
                array_push($this->errorArray,Constants::$passwordLength);
            }

        }
        public function getError($error){
            if(in_array($error,$this->errorArray)){
                return "<span class='error-msg'>$error</span>";
            }
        }
    }
?>