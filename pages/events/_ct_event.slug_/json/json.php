<?

$o = new ct_event($ct_event_id);
$event = new event($o, $website);

$data = $event->data;

$data['buy_url'] = 'http://' . $website->domain . $data['buy_url'];

foreach ( $data['categories'] as $i => $cat ) {
	unset($data['categories'][$i]['ct_category_id']);
}

unset($data['id']);
unset($data['market_id']);
unset($data['tags']);

foreach ( $data['tickets'] as $i => $ticket ) {
	unset($data['tickets'][$i]['ct_ticket_id']);
	unset($data['tickets'][$i]['_object']);
}

unset($data['venue']['venue_type_assign']);
unset($data['venue']['ct_barcrawl_area_id']);
unset($data['venue']['market_id']);
unset($data['venue']['market_nbhd_id']);
unset($data['venue']['venue_type_id']);
unset($data['venue']['ct_promoter_id']);
unset($data['venue']['venue_id']);
unset($data['venue']['id']);
unset($data['venue']['images_vfolder']);
unset($data['venue']['logo_vfolder']);
unset($data['venue']['nye_party_pics_vfolder']);
unset($data['venue']['halloween_party_pics_vfolder']);
unset($data['event_flyer']);
unset($data['event_flyer_branded']);

//krumo($data);
header('Content-type: application/json');
echo json_encode($data);