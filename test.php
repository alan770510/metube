<?php
require_once('./includes/head.php');
?>
<div class="container">
    <div id="show">

    </div>
    <nav aria-label="Page navigation">
        <ul class="pagination" id="pagination"></ul>
    </nav>
</div>
<script type="text/javascript">
    $(function () {
        $.ajax({
            type:'GET',
            url:'URL',
            success:function( data ){
                //data = 假設妳的data 取得的資料如mycars 用mycars來舉例
                var mycars = new Array();
                mycars[1] = "a";
                mycars[2] = "b";
                mycars[3] = "c";
                mycars[4] = "d";
                mycars[5] = "e";
                mycars[6] = "f";
                mycars[7] = "g";
                mycars[8] = "h";
                mycars[9] = "i";
                mycars[10] = "j";

                mycars_length = mycars.length;
                window.pagObj = $('#pagination').twbsPagination({
                    // totalPages如果妳一頁最多顯示2筆資料,那總長度就是除2 所以有5個分頁
                    totalPages: mycars_length/2,
                    visiblePages: 2,
                    onPageClick: function (event, page) {
                        console.info(page + ' (from options)');
                        // 所以第1頁顯示mycars的1,2  2頁->3,4  3頁->5,6
                        // text()顯示妳的資料
                        // 在text()當中可以適當穿插css
                        $('#show').text( "data1:"+ mycars[page*2-1]+"   data2:"+mycars[page*2]);
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
