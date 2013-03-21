<? 

// quickjump and event details
ps(array(
	'title' => '',
	'hide_back_to_top' => true,
	'body' => function() use($profile) {

		$qj_array['description'] = 'Description'; 
		$qj_array['tickets'] = 'Tickets';
		$qj_array['music_crowd'] = 'Music &amp; Crowd'; 
		$qj_array['dress_code'] = 'Dress Code'; 
		$qj_array['age'] = 'Age';
		$qj_array['menu'] = 'Menu';

		global $spreadsave;
		if($spreadsave[$profile->website->ct_promoter_website_id]) {
			$spreadsave = $spreadsave[$profile->website->ct_promoter_website_id];
			$profile->spreadsave_html = '
			<div class="has-floats" name="qj_coupon">
				<div class="has-floats">
					<div class="float-right">
						<a class="back_to_top" href="#totop">Back To Top</a>
					</div>
					<h4>Coupon</h4>
				</div>
				<div class="qj_content has-floats">
					Want to save money on '.$spreadsave['save_on'].'?
					<a style="text-decoration:underline" rel="nofollow" href='.$spreadsave['url'].'>Get your coupon code now</a> and enter it at checkout.
				</div>
			</div>';
			$spreadsave_qj = '<li><a href="#qj_coupon" class="quick_jump">Coupon</a></li>';
		}	

		$quick_jump = array_filter(array_keys($qj_array), 
			function($n) use($profile) {
				if (is_bool($n)) return $n;
				return (trim(strip_tags($profile->ct_event->preparedData[$n])));
			}
		);
?>
		<div class="qj_cont has-floats">
			<nav id="quick_jump" class="float-left">
				<ul>
<?
					foreach ($quick_jump as $j) {
?>
						<li>
							<a href="#qj_<?=$j?>" class="quick_jump">
								<?=$qj_array[$j]?>
							</a>
						</li>
<?						
						if($j== 'tickets' && $spreadsave_qj) echo $spreadsave_qj;
					} 
?>
				</ul>
			</nav>


			<div class="qj_to float-left">
<?

				foreach ($quick_jump as $j) {
					
					// if not tickets just display the data
					if ($j != 'tickets') { 
						section::qj($j, $profile->ct_event); 
						continue; 
					}

					// if tickets we have a lot of stuff to do
					section::qj($j, function() use($profile) {
						
						array_walk($profile->event->tickets, function($t) use($profile) {

							$website = $profile->website;
							$o = $profile->ct_event;
							$event = $profile->event;
							$description = strip_inline_style($t['description']);
?>							
							<div class="display_ticket has-floats" 
								 name="ticket_<?=$t['ct_ticket_ide']?>">
                            	<a name="<?=$t['ct_ticket_ide']?>"></a>
								<div class="ticket-info float-left 
									 <?=(!$description)?'full-width':''?>">
									<h6><?=$t['name']?></h6>
									<div class="has-floats">
										<? section::ticketbox($t, $event); ?>
									</div>
<?
								if ( count($t['bottle_allocation']) > 0 ) {
?>
									<table class="list">
										<tr>
											<th>Tables Of</th>
											<th># Of Bottles</th>
										</tr>
<?
									$last_range = null;
									foreach ($t['bottle_allocation'] as $b) {
									
										$range = $b['range'];
										$row_class = 'top-border';

										if ($last_range == $range) {
											$row_class = $range = '';
										} 
?>
										<tr class="<?=$row_class?>">
											<td><?=$range?></td>
											<td><?=$b['num_bottles']?> <?=$b['bottle_name']?></td>
										</tr>
<?											
										if ($range) {
											$last_range = $range;
										}

									} // end foreach bottle allocation
									
									if ($event->liquor['vodka'] || $event->liquor['champagne']) {
										$has_star = true;
										if ($event->liquor['vodka'] && $event->liquor['champagne']) {
											$text = ' * The vodka provided for this event is ' 
												  . $event->liquor['vodka'] 
												  . ' and the champagne is '
												  . $event->liquor['champagne'];
										} else if ($event->liquor['champagne']) {
											$text = ' * The champagne provided for this event is '
												  . $event->liquor['champagne'];
										} else {
											$text = ' * The vodka provided for this event is '
												  . $event->liquor['vodka'];
										}
?>
										<tr>
											<td colspan="2" class="bottle-header top-border">
												<strong><?=$text?></strong>
											</td>
										</tr>
<?										
									} // end if has alcohol
								
									if ($o->isUpcomingEvent()) {
?>
										<tr>
											<td colspan="2" class="top-border red">
												<strong>
													<?=($has_star)?'*':''?>
													* You can only select a table size that is
													available in the shopping cart.
													Some table sizes may no longer be available.
												</strong>
											</td>
										</tr>
<?										
									} // end if is upcoming
?>										
									</table>
<?
								} // end if bottle allocation
?>
								</div>
<?
								if ($description) {
?>
								<div class="float-left ticket-description">
									<?=$description?>
								</div>
<?									
								}
?>								
						</div>
<?
		
						}); // end foreach ticket

					}); // end quick jump section

					// put in spread save if right after tickets
					if($j=='tickets' && $profile->spreadsave_html) echo $profile->spreadsave_html;
				} // end for each quick jump
?>
			</div>
		</div>
		<div id="quick_jump_after"></div>
<?
	}
)); // end event details section