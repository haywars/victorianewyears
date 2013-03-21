<?
// $event = new event($o, $website);
//krumo($event()); 
//krumo($o);
//krumo($event_api);
////krumo($this);
?>

<div class="section has-floats">
	<div id="back_link_container">
		<div>
            <a id="back_to_all" href="/<?=$o->ct_contract['market_slug']?>/<?=$o->ct_contract->ct_contract_category[0]['category_slug']?>">Back</a>
<?
			$vars = array(
				'upcoming' => true,
				'status' => 'A',
				'market_id' =>  ($o->is_bc) ? $o->ct_contract->market_id : $o->venue->market_id,
				'ct_category_id' => $o->ct_contract->ct_contract_category[0]['ct_category_id'],
				'order_by' => 'venue.name asc'
			);


			if (sizeof($p->vars['date_array']['unsorted_ids']) > 1 && !$event_api->is['bc']) {
?>
            <a id="show_event_flyout" class="up">Show All Events</a>
            <div id="next-prev">
<?
				$curr_key = array_search($o->ct_event_id,$p->vars['date_array']['unsorted_ids']);
				$prev_id = $curr_key-1;
				if ($prev_id < 0) $prev_id = sizeof($p->vars['date_array']['unsorted_ids'])-1;
				$next_id = $curr_key+1;
				if ($next_id == sizeof($p->vars['date_array']['unsorted_ids'])) $next_id = 0;
				
				$prev_event = new ct_event_min($p->vars['date_array']['unsorted_ids'][$prev_id]);
				$next_event = new ct_event_min($p->vars['date_array']['unsorted_ids'][$next_id]);
				?>
                <a id="prev_button" title="<?=$prev_event->getName()?>" href="/events/<?=$prev_event->slug?>">&laquo; Prev</a>
                <span class="pipe">|</span>
                <a id="next-button" title="<?=$next_event->getName()?>" href="/events/<?=$next_event->slug?>">Next &raquo;</a>
            </div>
<?
			} // end if more than one ct_event_id
?>
        </div>
        <div class="more_events_flyout">
        	<div class="col">
<? 	
			$count = null;
			$split = ceil(sizeof($p->vars['date_array']['unsorted_ids'])/4); 
			foreach ($p->vars['date_array'] as $key => $date) {
				if($date['events']) {
					foreach ($date['events'] as $flyout_event) {
						$count++;
?>
		                <div class="flyout_event" <?=$flyout_event['uri']==$p->urlpath?"selected":""?>><a href="<?=$flyout_event['uri']?>"><?=$flyout_event['name']?></a></div>	
<?
						if ($count%$split==0 && $count != sizeof($listing->ct_event_ids) ) {
?>
							</div>
			            	<div class="col">
<?
						}
						
					}
				}
			} // end foreach cols
?>
        	</div>
        </div>
<?
		global $eventListing2;
		global $upcoming;

		if(strtotime($o->ct_contract->start_date) < strtotime('yesterday')) {
?>	
			<div id="this-already-happened">
				This Event Already Happened
<?
			if ($eventListing2->ct_event_ids[0]) { 
?>
				<a href="/events/<?=$upcoming->slug?>">Click here for upcoming events<?=(!$o->is_bc)?' at '.$o->venue->venue_name:''?></a>
<? 
			} else { 
?>
				<a href="/">Click here for this year's lineup</a>
<? 
			} 
?>
			</div>
<?
		} // end if this is a past event
?>
	</div>	
	<div id="profile-headline">
<?
		if($event_api->is['bc']) {
			$event->inverse_box = null;
		}
		
		if ($event->inverse_box) {
?>
			<h1 id="inverse-box"><?=$event->inverse_box?></h1>	
<?			
		}		
?>		      
        <iframe src="http://beta.cravetickets.com/iframe/edit-event?ide=<?=$o->ct_event_ide?>" scrolling="no"></iframe>
        <div>
<? 
			$headings = array(
				'title' => 1,
				'subtitle' => 2
			);

			if ($event->inverse_box) {
				foreach ($headings as $key => $value) {
					$headings[$key]++;
				}
			}

?>
			<h<?=$headings['title']?> id="title"><?=$event_api->name?></h<?=$headings['title']?>>
			<h<?=$headings['subtitle']?> id="subtitle"><?=$event->subtitle?></h<?=$headings['subtitle']?>>

		</div>
        
		<div class="bottom-padding" id="promotion"><?=$event->promotion?></div>
        
		<div class="bottom-padding main-datetime">
			<span class="main-date">
				<?=$o->preparedData['date_range']['first']; ?>
				<?=($o->preparedData['date_range']['last']) ? ' - '.$o->preparedData['date_range']['last'] : ''; ?>
			</span>
			<span class="main-time">
				<?=($event_times['event_start_time'])?:$event_times['door_time']?>
				<?=($event_times['close_time'])?' - ' . $event_times['close_time']:null?>
			</span>
		</div>
        
<?
		if($o->ct_contract->online_ticketing == 2 && $o->buy_url && sizeof($o->bottleTickets) == 0 ) {
?>
        	<a class="buy-button" href="<?=$o->buy_url?>" target="_blank">Buy Tickets</a>
<?
		}
?>
	</div>
</div>