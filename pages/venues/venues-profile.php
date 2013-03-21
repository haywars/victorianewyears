<?php
$venue = new venue(decrypt(IDE,'venue'));
$market = new market($venue->market_id);

if (!$venue) redirect('/'.$_SESSION['market_slug'].'/venues' );

$this->title = $venue->venue_name.' '.$venue->name_modifier.' '.$venue->venue_address;
$this->tab = 'venues';
$this->breadcrumb = array(
    'Home' => '/',
    $venue->city.' Venues' => '/venues',
    $venue->venue_name => null
);
$this->css[] = '/pages/_market.slug_/_blog.slug_/_blog.slug_.css';
$this->template('website','top');

//REDIRECT DELETED VENUES

$banner_w = 974;
$banner_h = 212;
$vfolder_path = '/venues/'.$venue->getID().'/profile-header';
$params = array('min_width' => $banner_w,
    'min_height'=> $banner_h,
    'upsize'=>true
);
$banner = vf::getRandomItem($vfolder_path,$banner_w,$banner_h,true,NULL,$params);
if (!$banner->html) {
    $vfolder_path = '/venues/' . $venue['venue_id'];
    $banner = vf::getRandomItem($vfolder_path,$banner_w,$banner_h,true,NULL,$params);
}
if($banner->html){
?>
    <div id="venue_banner">
        <?=$banner->html?>
        <h1 id="venue_title" class="univers"><?=$venue['venue_name']?></h1>
    </div>
<?
}else{
?>
	<h1 class = "jb-h1"><?=$venue->name?></h1>
<?
}
?>
    <div id = "venue_info" style="overflow: hidden;">
        <div>
<?
            include 'includes/venue-details.php';
            include 'includes/venue-events.php';
            include 'includes/venue-twitter.php';
?>
        </div>
        <div>
<?
            include 'includes/venue-photos.php';
            include 'includes/venue-map.php';
            include 'includes/venue-video.php';
?>
        </div>
        <div>
<?
            include 'includes/ad.php';
            include 'includes/venue-tips.php';
?>
        </div>
    </div>
<?
$this->template('website','bottom');