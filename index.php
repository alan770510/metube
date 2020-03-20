
   <?php
        require_once('./includes/head.php');
        if(isset($_SESSION['userLoggedIn'])){
            echo 'Welcome '.ucfirst($userLoggedInObj->getUsername()).' to MeTube';
        }
   ?>
    </div>
    </div>
</div>
</body>
</html>