<?
if(!$this->tab) {
	$this->tab = 'events';
}

/*
 * 	If this is an IDE redirect to event profile
 */
if ($p->ide && decrypt($p->ide, 'ct_event')) {
	redirect('/events/'.$p->ide);
}

/*
 *  if no criteria is already specified, use this default criteria.
 *  this should generally never happen but is here as a fallback
 */
if (!$event_criteria) {

	$event_criteria = array(
		'upcoming' => true,
		'status' => 'A'
	);

}

$eventListingAllDates = new EventListing($event_criteria);
$eventListingAllDates->useWebsiteFilter($website);
$eventCriteriaAllDates = array_merge( $event_criteria, $eventListingAllDates->vars );

if(is_numeric(str_replace('-','',$this->queryfolders[0]))) {
	$date_parts = explode('-', $this->queryfolders[0]);
	if (sizeof($date_parts) == 3) {
		$event_criteria['date'] = implode('/', $date_parts);
	}
}
if( $_GET['date'] )
	$event_criteria['date'] = str_replace('-','/',$_GET['date']);
if( $_GET['start_date'] )
	$event_criteria['start_date'] = $_GET['start_date'];
if( $_GET['end_date'] )
	$event_criteria['end_date'] = $_GET['end_date'];
/*
 *  add the additional criteria specified by the website settings and search
 */

/*
 * Log the search if there is a search
 */
if($_GET['q']) {
	$test = ct_search::log_search(array(
	    'query' => $_GET['q'],
	    'ct_promoter_website_id' => $website->ct_promoter_website_id
	));
}

$eventListing = new EventListing($event_criteria);
$eventListing->useWebsiteFilter($website);
$eventListing->setSearch($_GET['q']);
$event_criteria = array_merge( $event_criteria, $eventListing->vars );

/*
 *  get the array of event_id's matching our criteria
 */

$events = ct_event::getList($event_criteria);

// FORMAT PAGE H1
if (!$h1) {
	if ($market_nbhd_id) {
		$market_nbhd_name = aql::value('market_nbhd.name',$market_nbhd_id);
		if ($p->vars['ct_category_name'])
			$h1 = $p->vars['ct_category_name']." In ".$market_nbhd_name;
		else
			$h1 = $market_nbhd_name." Parties";
	}
	elseif ($venue_type_id) {
		$venue_type_name = aql::value('venue_type.name',$venue_type_id);
		if ($p->vars['market_name'])
			$h1 = $venue_type_name." Parties In ".$p->vars['market_name'];
		else
			$h1 = "Parties at ".$venue_type_name;
	}
	elseif ($p->vars['market_name']) {
		if($p->vars['ct_category_name'])
			$h1 = $p->vars['market_name'].' '.$p->vars['ct_category_name'].' Parties';
		else
			$h1 = $p->vars['market_name'].' Parties';
	}
	else {
		if($p->vars['ct_category_name'])
			$h1 = $p->vars['ct_category_name'].' Parties';
		else
			$h1 = 'Parties';
	}
}

if (!$p->title) $p->title = $h1;

if ($p->seo['title']) $p->title = $p->seo['title'];
// if only one event and we're not viewing a specific date
if (count($events) == 1 &&
	!$event_criteria['date'] &&
	!$event_criteria['start_date'] &&
	!$event_criteria['end_date']) {

    // show the profile page since there is only 1 event
    if (!$search) $do_not_redirect = true;
	$ct_event_id = $events[0];
	$p->css[] = '/pages/events/_ct_event.slug_/_ct_event.slug_.css';
	$p->js[] = '/pages/events/_ct_event.slug_/_ct_event.slug_.js';
	include 'pages/events/_ct_event.slug_/_ct_event.slug_-settings.php';
	include 'pages/events/_ct_event.slug_/_ct_event.slug_-script.php';
	include 'pages/events/_ct_event.slug_/_ct_event.slug_.php';

} else {
	/*
	 *  include the layout that this website uses
	 */

	$layout = $website->getField('layout_type') ?: 'left-nav-standard';

    // show the listing page since there is more than 1 event
    $p->css[] = "/lib/layouts/" . $layout . "/" . $layout . ".css";
    $p->js[] = "/lib/layouts/" . $layout . "/" . $layout . ".js";
    include( 'lib/layouts/' . $layout . '/' . $layout . '.php' );

}

