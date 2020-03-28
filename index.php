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
<!--category button event handler-->
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
           echo '<div id ="allvideopage"> </div>';
           echo '<div id="page-nav">
           <nav aria-label="Page navigation">
               <ul class="pagination" id="pagination"></ul>
           </nav>
       </div>';
       }
       ?>

   </div>
   </div>
<!--   main-video-container div end-->

   <script type="text/javascript">
       $(function () {
           $.ajax({
               type:'POST',

               url:'showallvideoprocess.php',
               data:{
                   showallvideo:"1"
               },
               datatype:'json',
               success:function(result){

                   final = JSON.parse(result);


                   datalength = final.length;

                   window.pagObj = $('#pagination').twbsPagination({
                       // totalPages如果妳一頁最多顯示4筆資料,那總長度就是除4
                       totalPages: datalength /4,
                       visiblePages: 5,
                       onPageClick: function (event, page) {
                           console.info(page + ' (from options)');
                           document.getElementById("allvideopage").innerHTML=final[page*4-4]+final[page*4-3]+final[page*4-2]+final[page*4-1] ;
                       }
                   }).on('page', function (event, page) {
                       console.info(page + ' (from event listening)');
                   });

               }
           });
       });
   </script>





   </div>
    </div>
</div>
</body>
</html>