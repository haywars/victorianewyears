<?

// the event slug is invalid

// check to see if it's an old url and redirect to the new profile page
$ct_event_id = decrypt(IDE,'venue');
if ( is_numeric( $ct_event_id ) ) {
    $event = new ct_event( $ct_event_id );
    // means this is probably an older event
    if (!$event->slug) {
    	$event->autoGenerateSlug();
    	if ($event->status != $event->ct_contract->status && in_array($event->ct_contract->status, ct_event::$active_statuses)) {
    		$event->saveProperties(array(
    			'status' => $event->ct_contract->status
	    	));
    	}
    }
    redirect('/events/' . $event->slug);
}

redirect('/');