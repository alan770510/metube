<ul class=\"nav nav-tabs \" id=\"myTab1\">
<li id=\"channel1\" class=\"active\">
    <a data-toggle=\"tab\" href=\"#channel2\">Channel</a></li>
<li><a data-toggle=\"tab\" href=\"#mySubscriptions\">My Subscriptions</a></li>
<li id=\"myPlayList1\" ><a data-toggle=\"tab\" href=\"#myPlayList2\">My Playlist</a></li>
<li><a data-toggle=\"tab\" href=\"#menu3\">Menu 3</a></li>
</ul>

<div class=\"tab-content\">
    <div id=\"channel2\" class=\"tab-pane fade in active\">
    <form action=\"channelprocess.php?channel=$this->user\" method=\"post\">
        $deletebutton
        <div id=\"show\">
        </div>
    </form>
    <div id=\"page-nav\">
        <nav aria-label=\"Page navigation\">
        <ul class=\"pagination\" id=\"pagination\"></ul>
        </nav>
    </div>
</div>
<div id=\"mySubscriptions\" class=\"tab-pane fade\">
<div id=\"showSubscriptions\"></div>
</div>
<div id=\"myPlayList2\" class=\"tab-pane fade\">
$createPlaylistButton
<div id=\"showMyPlayList\"></div>
</div>
<div id=\"menu3\" class=\"tab-pane fade\">
<h3>Menu 3</h3>
<p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
</div>
</div>
<!--             --><?php
//                if(isset($_GET['tab'])) {
//                    echo "$('#myTab1 a[href=\"#myPlayList2\"]').tab('show');";
//                }
//             ?>

<ul class="nav nav-pills mb-3" id="myTab1" role="tablist">
    <li id="channel1" class="nav-item">
        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#channel2" role="tab" aria-controls="pills-home" aria-selected="true">Channel</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#mySubscriptions" role="tab" aria-controls="pills-profile" aria-selected="false">My Subscriptions</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#myPlayList2" role="tab" aria-controls="pills-contact" aria-selected="false">My Playlist</a>
    </li>
</ul>
<div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="channel2" role="tabpanel" aria-labelledby="pills-home-tab">
        <form action=\"channelprocess.php?channel=$this->user\" method=\"post\">
            $deletebutton
            <div id=\"show\">
            </div>
        </form>
        <div id=\"page-nav\">
            <nav aria-label=\"Page navigation\">
            <ul class=\"pagination\" id=\"pagination\"></ul>
            </nav>
        </div>
    </div>
    <div class="tab-pane fade" id="mySubscriptions" role="tabpanel" aria-labelledby="pills-profile-tab">
        <div id=\"showSubscriptions\"></div>
    </div>
    <div class="tab-pane fade" id="myPlayList2" role="tabpanel" aria-labelledby="pills-contact-tab">
        $createPlaylistButton
        <div id=\"showMyPlayList\"></div>
    </div>
</div>