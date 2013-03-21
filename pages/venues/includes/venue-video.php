<?php
if ($venue->walkthrough_youtube_url) {
    $url = parse_url($venue->walkthrough_youtube_url);
    parse_str($url['query'], $qs);
    if ($qs['v']) {
        $youtube_id = $qs['v'];
    } elseif ( strpos($venue->walkthrough_youtube_url,'ttp://youtu.be/') ) {
        $youtube_id = str_replace('http://youtu.be/','',$venue->walkthrough_youtube_url);
    }

    if ($youtube_id) {
?>
<div class = "grid_item">
    <h3 class = "univers">VIDEO WALK THROUGH</h3>
    <div class = "grid_item_content">
        <iframe width="313" height="230" src="http://www.youtube.com/embed/<?=$youtube_id?>" frameborder="0" allowfullscreen></iframe>
    </div>
</div>
<?php
    }
}