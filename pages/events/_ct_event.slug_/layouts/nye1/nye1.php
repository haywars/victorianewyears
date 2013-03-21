<?php

// NYE1 profile Layout

global $profile_main_width;

elapsed('before getting folder venue pics');
$photos_vfolder_path = "/venues/{$o->venue_id}";
$vfolder = vf::getFolder($photos_vfolder_path);
elapsed('after getting folder venue pics');

$event_times = $o->getEventTimes();

$href = $_SERVER['SERVER_NAME'].$p->uri;

?>

<div id="profile-content">
	<div id="left-content">

<?
	include 'event-header.php';
	include 'venue-photos.php';
	include 'venue-walkthrough.php';
	include 'youtube-url.php';
	include 'event-details.php';
	include 'fb-comments.php';

	// more parties
	// disabled currently

?>
	</div>
	<div id="side-content">
		<div>
        	<? include 'social-media.php'; ?>
			<? include 'right-content.php'; ?>
		</div>
	</div>
</div>
