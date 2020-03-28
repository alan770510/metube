<?php
require_once('./includes/head.php');
require_once('./includes/classes/channelProcessor.php');
if(!isset($_GET['channel'])){
    echo "<script>alert('You are not choose any channel, redirect to Home page after click'); location.href = 'index.php';</script>";
}
$channel = new channelProcessor($con,$_GET['channel']);

?>
<div id ="channelPage">
<div class="container">
    <h2>Welcome to <?php echo ucfirst($_GET['channel'])?>'s Channel</h2>
    <ul class="nav nav-tabs ">
        <li class="active"><a data-toggle="tab" href="#Channel">Channel</a></li>
        <li><a data-toggle="tab" href="#menu1">Menu 1</a></li>
        <li><a data-toggle="tab" href="#menu2">Menu 2</a></li>
        <li><a data-toggle="tab" href="#menu3">Menu 3</a></li>
    </ul>

    <div class="tab-content">
        <div id="Channel" class="tab-pane fade in active">


            <div id="show">

            </div>

            <div id="page-nav">
                <nav aria-label="Page navigation">
                    <ul class="pagination" id="pagination"></ul>
                </nav>
            </div>


        </div>
        <div id="menu1" class="tab-pane fade">
            <h3>Menu 1</h3>
            <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
        </div>
        <div id="menu2" class="tab-pane fade">
            <h3>Menu 2</h3>
            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
        </div>
        <div id="menu3" class="tab-pane fade">
            <h3>Menu 3</h3>
            <p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
        </div>
    </div>
</div>

</div>

<script type="text/javascript">
         $(function () {
        $.ajax({
            type:'POST',
            // url:'includes/classes/channelProcessor.php',
             url:'channelprocess.php',
            data:{
                pagefunction:"1"
            },
            datatype:'json',
            success:function(result){
                final = JSON.parse(result);
                // console.log(final);

                 datalength = final.length;
                console.log(final.length);
                window.pagObj = $('#pagination').twbsPagination({
                    // totalPages如果妳一頁最多顯示4筆資料,那總長度就是除4
                    totalPages: datalength /4,
                    visiblePages: 5,
                    onPageClick: function (event, page) {
                        console.info(page + ' (from options)');
                        document.getElementById("show").innerHTML=final[page*4-4]+final[page*4-3]+final[page*4-2]+final[page*4-1] ;
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