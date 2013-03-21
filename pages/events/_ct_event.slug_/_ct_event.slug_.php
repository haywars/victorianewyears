<?
$p->page_sidebar = 'no_sidebar';


// if (!$ct_event_id) {
// 	$ct_event_id = IDE;
// }

global $profile_main_width;

if (!$profile_main_width) $profile_main_width = 640;

// $do_not_redirect = true;

// make sure this event is allowed to be displayed on this website
elapsed('before getListing');

$vars = array(
	'ct_event_id' => $ct_event_id
);

if (!$_GET['preview']) {
    $vars['status'] = 'A';
}

if (!$vars['seller__ct_promoter_id']) {
    $vars['seller__ct_promoter_id'] = $website->ct_promoter_id;
}

$eventListing = new eventListing($vars);

elapsed('before get list');

$eventListing->useWebsiteFilter($website);
$eventListing->getList();
$ct_event_id = $eventListing->ct_event_ids[0];

elapsed('after get list');

if ( !$ct_event_id ) {
    include('pages/404.php');
    exit;
}



$event_api = new \Crave\Api\Event( array(
    'event' => $ct_event_id,
    'seller' =>  $website->ct_promoter->ct_promoter_id,
    'ct_promoter_website_id' => $website->ct_promoter_website_id,
    'min' => true
));
$o = $event_api->getevent();
$o->checkAnnouncedStatus();
$o->checkShutdownStatus();
$event = new event($o, $website);



$market_id = ($o->is_bc)
	? $o->ct_contract->market_id :
	$o->venue->market_id;

$market = new market( $market_id );


// side bar and flyout event listing
$mem_key = 'profile-date_array'
    . ':' . $market_id
    . ':' . $o->ct_contract->ct_contract_category[0]['ct_category_id']
    . ':' . $website->ct_promoter_website_id;
$date_array = mem($mem_key);
if (!$date_array) {
	$vars = array(
					'upcoming' => true,
					'status' => 'A',
					'market_id' =>  $market_id,
					'ct_category_id' => $o->ct_contract->ct_contract_category[0]['ct_category_id'],
					'order_by' => 'venue.name asc'
				);
	$side_listing = new EventListing($vars);
	$side_listing->useWebsiteFilter($website)->getList();

	foreach($side_listing->ct_event_ids as $ct_event_id) {
		$event_min = new ct_event_min($ct_event_id);
	    $date = strtotime($event_min->ct_contract_date[0]->start_date);
	    $date_array[$date]['count']++;
	    $date_array[$date]['date'] = date('n-j-Y', $date);
	    $date_array[$date]['events'][] = array(
	        'name' => $event_min->is_bc ? $event_min->event_name : $event_min->venue->venue_name,
	        'modifier' => $event_min->venue->name_modifier,
	        'time' => str_replace(':00','',date('g:ia',strtotime($event_min->door_time))),
	        'uri' => '/events/' . $event_min->slug
	    );
	}
	$date_array['unsorted_ids'] = $side_listing->ct_event_ids;
    mem($mem_key, $date_array, '30 minutes');
}
$this->vars['date_array'] = $date_array;
// end sidebar event listing


if ( $market->market_id ) {
    $p->vars['market_id'] = $_SESSION['market_id'] = $market->market_id;
    $p->vars['market_name'] = $_SESSION['market_name'] = $market->name;
    $p->vars['market_slug'] = $_SESSION['market_slug'] = $market->slug;
}

$resellerURL->setMarketInfo($market->slug);

$profile = new eventProfile($event, $p, $event_api);

$profile_type = if_not($website->getField('profile_layout_type'), function() {
	return reset(array_keys(ct_promoter_website::$profile_types));
});

if ($o->is_bc) {
	$profile_type = 'barcrawl';
}

$profile->setType($profile_type);
// $profile->setType('nye1');
// $profile->setType('nye2');

$profile->prepEvent($website, 'l, F jS, Y'); // uses website object for Href and ticket fees/prices

if (!$do_not_redirect) {
	$qs = ($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : '';
	// figure out based on sitemap
	redirect('/events/'.$profile->ct_event->slug.$qs, 302);
}

/*
// don't need this because we are using getList above to make sure it is viewable on this website - will
if (!$profile->isViewable()) {
	include('pages/404.php');
	exit;
}
*/

$p->title = $event_api->seo['title'];

$p->breadcrumb = array(
	'Home' => '/',
	$market->name.' Events' => '/'.$market->slug,
	$p->title => null
);

$p->tab = 'events';

$p->js[] = '/lib/js/GMAP.js';
$p->js[] = 'http://maps.google.com/maps/api/js?sensor=false&language=en&callback=initializeGMAPs';
if (!in_array('/lib/vfolder/css/vf.css', $p->css)) $p->css[] = '/lib/vfolder/css/vf.css';
if (!in_array('/lib/js/jquery.hoverIntent.js', $p->js)) $p->js[] = '/lib/js/jquery.hoverIntent.js';
if (!in_array('/lib/vfolder/js/vf.js', $p->js)) $p->js[] = '/lib/vfolder/js/vf.js';

// FACEBOOK METADATA
preg_match("/<p>(.*)<\/p>/U", $o->description, $matches);
$mediaIDs = $o->getMediaIDs(1);
$i = vf::getItem($mediaIDs[0]);
$p->head[]= '
	<meta property="og:title" content="'.$profile->page->title.'" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content=http://'.$website->domain.''.$p->uri.'/>
	<meta property="og:description" content="'.htmlentities(strip_tags($matches[1])).'" />
	<meta property="og:site_name" content="'.$website->domain.'"/>
	<meta property="og:image" content="'.$i->src.'" />
	<meta property="fb:admins" content="13800768,733443658" />
';

$profile->prepareTemplate($p);

$p->template('website', 'top');

if (strtotime($o->ct_contract->start_date) < strtotime('yesterday') ) {
	//CHECK IF THERE IS A CURRENT EVENT AT THIS VENUE
	$eventListing2 = new eventListing(array(
		'upcoming' => true,
		'venue_id' => $o->venue->venue_id,
		'market_id' => $market->getID(),
		'ct_category_id' => $o->ct_contract->ct_contract_category[0]['ct_category_id'],
		'upcoming' => true,
		'limit' => 1
	));
	$eventListing2->useWebsiteFilter($website);
	$eventListing2->getList();
	if ($eventListing2->ct_event_ids) {
		$upcoming = ct_event::getPartial($eventListing2->ct_event_ids[0]);
		$media_ids = $upcoming->getMediaIDs(1);
		$img = vf::getItem($media_ids[0], array('height'=>170,'width'=>300,'crop'=>'center'));


?>
	<div class="subcontent" id="an_upcoming_event">

        <div id="upcoming-info">
        	<div id="upcoming-campaign"><?=$upcoming->ct_contract->campaign_name?></div>
            <div id="upcoming-venue"><?=$upcoming->venue->venue_name?> <span><?=$upcoming->venue->name_modifier?></span></div>
            <div id="upcoming-subinfo">
            	<a href="/events/<?=$upcoming->slug?>"><img src="<?=$img->src?>" id="upcoming-venue-image" /></a>
            	<div id="upcoming-title"><?=($upcoming->is_bc)?$upcoming->ct_contract->ct_barcrawl->name:$upcoming->event_name?></div>
            	<a id="upcoming-see-more" href="/events/<?=$upcoming->slug?>">MORE INFO</a>
        	</div>
        </div>
        <a href="/<?=$o->ct_contract->market_slug?>/newyearseve"><img id='upcoming-ad' src='/pages/events/_ct_event.slug_/images/NYE2012_300x250_cobrand.jpg'  /></a>
    </div>
    <div id="scroll_down">scroll down to view last years event</div>

	<? } else { ?>
        <div id="upcoming-campaign-banner">
        	<a href="/<?=$o->ct_contract->market_slug?>/newyearseve"><img src="/pages/events/_ct_event.slug_/images/NYE2012_728X90.gif" /></a>
        </div>
    <? }

}


$profile->output();

$p->template('website', 'bottom');
