<?php

$profile_main_width = 540;

elapsed('before getting folder venue pics');
$photos_vfolder_path = "/venues/{$o->venue_id}";
$vfolder = vf::getFolder($photos_vfolder_path);
elapsed('after getting folder venue pics');

$event_times = $o->getEventTimes();

$href = $_SERVER['SERVER_NAME'].$p->uri;

$dir = 'pages/events/_ct_event.slug_/layouts/';
$nye1 = $dir.'nye1/';
$inc = function($a) use($nye1) { return $nye1.$a.'.php'; };

$p->css[] = '/lib/layouts/left-nav-standard/left-nav-standard.css';
$p->js[] = '/lib/layouts/left-nav-standard/left-nav-standard.js';

?>
<div id="profile-content" class="nye2"><?
    // include $inc('event-header');
?>
    <div id="left-nav">
<?
        include 'lib/layouts/left-nav-standard/left-nav.php';
?>
    </div>
    <div id="profile-body">
        <div id="event-header-container">
<?
            include $inc('event-header');
?>
        </div>
<?
        include $inc('venue-photos');
        include $inc('venue-walkthrough');
        include $inc('youtube-url');
        include $inc('event-details');
        // include $inc('fb-comments');
?>
    </div>
    <div id="right-sidebar">
<?
            include $inc('social-media');
            $buy_now_text = 'Buy';
            include $inc('right-content');
?>
    </div>
</div>
