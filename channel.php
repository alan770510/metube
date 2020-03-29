<?php
require_once('./includes/head.php');
require_once('./includes/classes/channelProcessor.php');
if(!isset($_GET['channel'])){
    echo "<script>alert('You are not choose any channel, redirect to Home page after click'); location.href = 'index.php';</script>";
}
$channel = new channelProcessor($con,$_GET['channel'],$usernameLoggedIn);


?>
<div id ="channelPage">

<div class="container">
    <h2>Welcome to <?php echo ucfirst($_GET['channel'])?>'s Channel</h2>
    <?php
    if(!strcmp($usernameLoggedIn,$_GET['channel'])) {
        echo $channel->showall();
    }
    else{
        echo $channel->showchannelonly();
    }
     ?>

</div>

</div>
<!--subscribe button alert modal-->
<div class="modal" id ="myModal" tabindex="-1" role="dialog" data-backdrop ='static' data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message</h5>
            </div>
            <div class="modal-body">
                <p id="subscribeResult"></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirm" class="btn btn-primary">Confirm</button>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
//    subscribe按鈕
    var subscribebtn = document.getElementById("subscribe");
    var unsubscribebtn = document.getElementById("unsubscribe");
    var whichbutton;
    if(subscribebtn){
         whichbutton = 1;
        subscribebtn.addEventListener("click", popup);
    }
    if(unsubscribebtn){
         whichbutton = 2;
        unsubscribebtn.addEventListener("click", popup);
    }
    function popup(){
        var user='<?php echo $_GET['channel']; ?>';
              $.ajax({
                type:'POST',
                url:'channelprocess.php',
                data:{
                    subscribe:"1",
                    button:whichbutton,
                    user:user
                },
                success:function(result) {
                    $('#myModal').modal("show");
                    document.getElementById("subscribeResult").innerText = result;

                }

                })

    };
//modal confirm button
$("#confirm").on('click', function() {
    <?php if($usernameLoggedIn) {
        echo 'location.href = \'channel.php?channel='.$_GET['channel'].'\';';
    }else{
        echo 'location.href = \'signIn.php\'';
    }?>

});


//            分頁按鈕    $(function () is jQuery short-hand for $(document).ready(function() { ... });
         $(function () {
             var user='<?php echo $_GET['channel']; ?>';
        //channel tab + page
        $.ajax({
            type:'POST',
            // url:'includes/classes/channelProcessor.php',
             url:'channelprocess.php',
            data:{
                pagefunction:"1",
                user:user
            },
            datatype:'json',
            success:function(result){
                final = JSON.parse(result);
                // console.log(final);

                 datalength = final.length;
                 if (datalength != 0){
                // console.log(final.length);
                window.pagObj = $('#pagination').twbsPagination({
                    // totalPages如果妳一頁最多顯示4筆資料,那總長度就是除4
                    totalPages: (datalength % 4) ?  (datalength /4) + 1: datalength /4,
                    visiblePages: 5,
                    onPageClick: function (event, page) {
                        var show =document.getElementById("show");
                        show.innerHTML="";
                        console.info(page + ' (from options)');
                        for ($i = 4; $i >0 ; $i--) {
                            if ( !(final[page * 4 - $i] == null)){
                                show.innerHTML += final[page * 4 - $i] ;
                            }
                        }

                    }
                }).on('page', function (event, page) {
                    console.info(page + ' (from event listening)');
                });
                     // show.innerHTML += '</form>';
                 }else{
                     document.getElementById("show").innerHTML ="";
                 }
            }
        });
    //    mysubscriptions

             $.ajax({
                 type:'POST',
                 url:'channelprocess.php',
                 data:{
                     mysubscribe:"1",
                     user:user
                 },
                 datatype:'json',
                 success:function(result){
                     final = JSON.parse(result);
                      console.log(final);
                     // https://www.w3schools.com/js/js_array_iteration.asp
                     final.forEach(arrayfunction);
                     function arrayfunction(value){
                         document.getElementById("showSubscriptions").innerHTML += value;
                     }

                 }
             });



    });
</script>

</div>
</div>
</div>
</body>
</html>