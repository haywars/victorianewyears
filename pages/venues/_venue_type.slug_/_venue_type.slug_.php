<?


$p->js[] = '/pages/events/_ct_event.slug_/_ct_event.slug_.js';
$p->css[] = '/pages/events/_ct_event.slug_/_ct_event.slug_.css';

$event_criteria = array(
	'upcoming' => true,
	'status' => 'A',
);

if($venue_type_id){
	$event_criteria['venue_type_id'] = $venue_type_id;
}

if($p->queryfolders[0] == 'embed')
	include('pages/embed/embed.php');
else
	include('includes/events-listing.php');