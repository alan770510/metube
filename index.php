   <?php
        require_once('./includes/head.php');
        require_once('./includes/classes/showAllVideo.php');
        if(isset($_SESSION['userLoggedIn'])){
            echo '<div id="main-video-container"> 
                  <div id ="welcomemessage">Welcome to MeTube,'
                .ucfirst($userLoggedInObj->getUsername()).'<br>';
        }
        if(isset($_GET['category'])){
           echo 'You are under category -'.$_GET['category'];
        }
        $showAllVideo = new showAllVideo($con);

   ?>
   </div> <!--   welcomemessage div end-->
<!--category browse button-->
   <div class="btn-group">
       <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           Category
       </button>
       <div class="dropdown-menu" id="categoryList">
           <a class='dropdown-item' href='#'>All</a>
           <?php
           echo $showAllVideo->getCategoryList();
           ?>
       </div>
   </div>

   <script>
       $(document).ready(function () {
           $('#categoryList a').on('click', function(){
               var category = ($(this).text());
               // alert(category);
               var href = "index.php?category="+category;
               window.location.assign(href);
           });
       });
   </script>

   <div id="showAllVideo">
       <?php
       If(isset($_GET['category'])){
           echo $showAllVideo->categoryFilter($_GET['category']);
       }else{
           echo $showAllVideo->create();
       }
       ?>
   </div>
   </div>
<!--   main-video-container div end-->


    </div>
    </div>
</div>
</body>
</html>