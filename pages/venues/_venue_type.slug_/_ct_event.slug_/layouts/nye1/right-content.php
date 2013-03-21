<?

// right content
if($o->ct_contract->online_ticketing == 2 && $o->buy_url /*&& sizeof($o->bottleTickets) == 0*/ ) {
?>
	<a class="buy-button" href="<?=$o->buy_url?>" target="_blank">Buy Tickets</a>
<?
}


if (count($event->tickets) >= 1)  {
	ps(array(
		'title' => '',
		'sidebar' => true,
		'body' => function() use($event, $buy_now_text) {
?>
			<ul class="ticket-list">
<?
			foreach ($event->tickets as $t) {
				if (!is_object($t['_object'])) continue;
?>
					<li class="has-floats">
						<div class="float-left ticket-name">
							<a class="quick_jump" 
								href="#ticket_<?=$t['ct_ticket_ide']?>">
								<?=$t['name']?>
							</a>
						</div>
						<? section::ticketbox($t, $event, 'BUY'); ?>
					</li>
<?
				} // end foreach ticket=
?>
			</ul>
<?
		}
	));
}
// flyer
$flyer_id = $o->getFlyer($website->getField('ct_promoter_id'));
$flyer = vf::getItem($flyer_id, array('width'=>220));
if ($flyer->html) {
	?>
		<a href="/events/skybox/flyer/<?=$flyer_id?>" skybox="true">
			<img src="<?=$flyer->src?>" />
		</a>
	<?
}
?>

					<div class="when-where has-floats">
						<div class="float-left title"><strong></strong></div>
						<div class="float-left">
							<? 
							$door_time = date('g:ia',$event_api->when['door_time']['U']);
							$close_time =  date('g:ia',$event_api->when['close_time']['U']);
							$timestring = $door_time.' - '.$close_time;

							$date = date('l, F jS Y', $event_api->when['date']['U']);
							?>
							<div><?=$date?></div>
							<div><?=$timestring?></div>
						</div>
					</div>
					
					<div class="when-where has-floats">
						<div class="has-floats">
							<div class="float-left title"><strong></strong></div>
							<div class="float-left">
								<div><?=$o->venue->venue_name?></div>
								<div><?=$o->venue->address1?></div>
								<div><?=$o->venue->city?> <?=$o->venue->state?> <?=$o->venue->zip?></div>
							</div>
						</div>
						<? if ($o->venue->latitude && $o->venue->longitude) : ?>
							<div class="somemapcont clear">
								<div id="venue_map" 
									venue_name="<?=$o->venue->venue_name?>" 
									lat="<?=$o->venue->latitude?>"
									lng="<?=$o->venue->longitude?>"
									address="<?=$o->venue->venue_address?>"
								></div>
							</div>
							<div class="float-right map-link-cont">
								<a href="<?=$o->venue->googleMapSearchLink()?>" class="map-link" target="_blank">&raquo; View Larger Map</a>
							</div>
						<? endif; ?>
					</div>





<?
// event tags
if ($o->ct_event_tag[0]->ctevent_tag_id > 0) {
	ps(array(
		'title' => 'Tags',
		'sidebar' => true,
		'body' => function() use($o) {
			foreach ($o->ct_event_tag as $i => $t) {
				?><div class="tag_container"><a href="/tag/<?=urlencode($t['name'])?>" class="event_tag <?=(!$i)?'first_tag':''?>"><?=$t['name']?></a></div><?
			}
		}
	));
}

// promoter company
if ($o->ct_contract->ct_promoter_company_id) {
	ps(array(
		'title' => 'Event By',
		'sidebar' => true,
		'body' => function() use($o) {
			$company = new ct_promoter_company($o->ct_contract->ct_promoter_company_id);
			echo $company->name;	
		}
	));
}