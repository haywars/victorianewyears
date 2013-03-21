<?

global $how_to_crawl_array;

$profile_main_width = 640;

$bars = $o->getBars();
$num_bars = count($bars);
$event_times = $o->getEventTimes();

$dir = 'pages/events/_ct_event.slug_/layouts/';
$nye1 = $dir.'nye1/';
$inc = function($a) use($nye1) { return $nye1.$a.'.php'; };

$href = $_SERVER['SERVER_NAME'].$p->uri;

if ($num_bars) {

	$display_bars = array_map(function($bar) {
		return array(
			'address' => $bar->venue_address,
			'lat' => $bar->latitude,
			'lng' => $bar->longitude,
			'venue_name' => $bar->venue_name,
			'is_registration_point' => $bar->is_registration_point
		);
	}, $bars);

?>
<script type="text/javascript">
	var EventBars = <?=json_encode($display_bars)?>;
</script>
<?

} // end if num_bars -- 

// krumo($o->_data);
?>
<div id="profile-content" class="bc1">
	<div id="left-content">
	<?
		include $inc('event-header');

		ps(array(
			'title' => '',
			'hide_back_to_top' => true,
			'body' => function() use($event) {
				?>
					<ul id="tickets_container">
				<?
					foreach ($event->tickets as $t) {
						$has_tickets = true;
						?><li>
							<div class="ticket-name"><a href="#ticket_<?=$t['ct_ticket_ide']?>" class="quick_jump"><?=$t['name']?></a></div>
							<? section::ticketbox($t, $event); ?>	
						</li><?
					}
				?>
					</ul>
				<?
					if (!$has_tickets) return;
				?>
					<p><strong>Prices Subject To Change</strong></p>
				<?
			}
		));

		if(!$o->ct_contract->ct_barcrawl->has_no_registration) {
			ps(array(
				'title' => 'Registration',
				'hide_back_to_top' => true,
				'id' => 'reg_info_container',
				'body' => function() use($profile) {
					
					$o = $profile->ct_event;

					$reg_pts = $o->getRegPoints();
					$num_points = $o->getNumRegPoints();

	?>
					<div id="registration_info">
						<p>Select a registration location at the site where you wish to start your crawl.</p>
	<?
					if ($num_points) {
	?>
						<p>This event has <strong><?=$num_points?></strong> start locations where you can recieve your crawl "stuff."</p>
	<?					
					} else {
	?>
						<p class="red">Registration points TO BE ANNOUNCED.</p>
	<?					
					}
	?>					
						<p><small>See Map For Details</small></p>
					</div>
	<?
					if (!$num_points) return;
	?>				
					<ol id="registration_points">
	<?
					foreach ($reg_pts as $bar) {
	?>
						<li>
							<div class="reg_point_time">Registration Time: <?=$bar->getRegTime()?></div>
							<div class="reg_point_name"><?=$bar->venue_name?></div>
							<div class="reg_point_address"><?=$bar->venue_address?></div>
						</li>
	<?						
					} // end foreach bar
	?>
					</ol>
	<?				
				}

			));
		}

		include $inc('youtube-url');

		ps(array(
			'title' => 'Map &amp; Bars',
			'hide_back_to_top' => true,
			'body' => function() use($profile, $bars, $num_bars) {

				if (!$num_bars) {
?>
					<p id="no_bars">
						Check back soon for an updated list of the bars in this crawl!
					</p>
<?					
					return; // if no bars, exit this section
				}
?>
				<div id="map_bars">
					<div id="map_cont">
						<div id="crawl_map"><div id="map"></div></div>
					</div>
				</div>
				
				<div id="bars_list">
<?
				$prev_area = null;
				$class = 'reg_point_icon';

				foreach ($bars as $i => $bar) {
					if ($bar->ct_barcrawl_area_id != $prev_area) {
?>
					<?=($i > 0) ? '</ol>' : ''?>
					<h4 class="barcrawl_area_name"><?=$bar->area_name?></h4>
					<ol start="<?=($i + 1)?>">
<?
					} // end headers

					$prev_area = $bar->ct_barcrawl_area_id;
?>
						<li>
<?
						if ($bar->is_registration_point) {
?>
							<div class="reg_pt_cont">
								<div class="<?=$class?>">Registration Point</div>
							</div>
<?						
						}
?>							
							<div class="bar_name"><?=$bar->venue_name?></div>
							<div class="bar_address"><?=$bar->venue_address?></div>
						</li>			
<?					
				}			
?>					</ol>
				</div>
<?				
			}
		));

		include $inc('event-details');

?>
	</div>
	<div id="side-content">
		<div>
<?
		include $inc('social-media');

		$flyers = $profile->getFlyers();
		if (is_array($flyers)) {
			foreach ( $flyers as $flyer_info ) {
				$flyer_id = $flyer_info['_id'];
				$flyer = vf::getItem($flyer_id, 300);
				if ($flyer->html) {
?>
					<a href="/events/skybox/flyer/<?=$flyer_id?>" skybox="true">
						<img src="<?=$flyer->src?>" width="300" />
					</a>
<?					
					break;
				}
			}
		}

		if ($website->sidebar_html) {
			?><div id="website_sidebar_html">
				<?=$website->sidebar_html?>
			</div><?
		}
?>
		<div class="when-where">
			<div>
				<? 
					$def_door_time = date('ga', strtotime($o->ct_contract->door_time));
					if ($o->ct_contract['event_start_time']) 
						$event_start_time = date('ga', strtotime($o->ct_contract->event_start_time));
					if ($o->ct_contract->close_time) 
						$def_close_time = date('ga', strtotime($o->ct_contract->close_time));
					foreach ($o->ct_contract->dates as $d) : 
						if (!strtotime($d['start_date'])) continue;
						$date = date('l, F jS Y', strtotime($d['start_date']));
						$door_time = ($d['start_time']) ? date('ga', strtotime($d['start_time'])) : $def_door_time;
						$close_time = ($d['end_time']) ? date('ga', strtotime($d['end_time'])) : $def_close_time;
						if ($event_start_time) {
							$timestring = 'Doors Open: '.$door_time.' | '.$event_start_time;
						} else {
							$timestring = $door_time;
						}
						if ($close_time) $timestring .= ' - '.$close_time;
						?>
							<div id="ww_date"><?=$date?></div>
							<div id="ww_time"><?=$timestring?></div>
						<?
					endforeach;
				?>
			</div>
		</div>
<?		

		if ($specials = $o->getSpecials()) {
?>
			<div id="specials">
				<div id="specials-title">
					Drink Specials
				</div>
				<ul>
<?
				foreach ($specials as $r) {
?>
					<li>
						<div class="special_name"><?=$r->special_name?></div>
						 <? if ($r->special_price > 0) { ?>
						<div class="special_price">$<?=number_format($r->special_price)?></div>
						<div class="special_details"><?=$r->special_details?></div>
                        <? } else { ?>
                        <div class="special_price"><?=$r->special_details?></div>
                        <? } ?>
					</li>
<?						
				}
?>					
				</ul>
				<p class="small">
					Note: Drink specials may vary by time and location.
				</p>			
			</div>
<?			
		}

		if ($num_bars) {
			ps(array(
				'title' => 'Crawl Pics',
				'sidebar' => true,
				'body' => function() use($o) {
					$w = 300;
					$h = 200;
					$photos = $o->getCrawlPics($w, $h);
					if (!$photos) return;
					echo vf::slideshow(array(
						'items' => $photos,
						'width' => $w,
						'height' => $h,
						'thumb_width' => 94,
						'thumb_height' => 70,
						'limit' => 6
					))->html;
				}
			));
		}

		// disable refresh -- but restore the original value later
		$temp_refresh = $_GET['refresh'];
		$_GET['refresh'] = false;



		elapsed('neighborhoods');

			#### NEIGHTBORHOOD ####
			if ($o->venue->market_nbhd_id) {
				$vars = array(
					'upcoming' => true,
					'status' => 'A',
					'market_id' =>  $o->ct_contract->market_id,
					'ct_category_id' => $o->ct_contract->ct_contract_category[0]['ct_category_id'],
					'market_nbhd_id' => $o->venue->market_nbhd_id,
					'order_by' => 'venue.name asc'
				);
				$listing = new EventListing($vars);
				$listing->useWebsiteFilter($website)->getList(); 
				if (sizeof($listing->ct_event_ids)>1) {
				?>
				<div class="padded-section">
					<h3>Other <?=$o->venue->nbhd_name?> Events</h3>
					<ul>
						<? 
						foreach ($listing->ct_event_ids as $event_id) {
							if($o->ct_event_id != $event_id) {
								$event = new ct_event_min($event_id);
								?>
									<li class="option"><a href="/events/<?=$event->slug?>" class="option"><?=$event->getName()?></a></li>
								<?
							}
						}
						?>
					</ul>
				</div>
				<?
				}
			}




			elapsed('venue type');	

			#### VENUE TYPE BOX ####
			$vars = array(
				'upcoming' => true,
				'status' => 'A',
				'market_id' =>  $o->ct_contract->market_id,
				'ct_category_id' => $o->ct_contract->ct_contract_category[0]['ct_category_id'],
				'venue_type_id' => $o->venue->venue_type_id,
				'order_by' => 'venue.name asc'
			);
			$listing = new EventListing($vars);
			$listing->useWebsiteFilter($website)->getList(); 
			if (sizeof($listing->ct_event_ids)>1) {
			?>
            <div class="padded-section">
            	<h3>Other <?=$o->venue->venue_type_name?> Events</h3>
                <ul>
                	<? 
					foreach ($listing->ct_event_ids as $event_id) {
						if($o->ct_event_id != $event_id) {
							$event = new ct_event_min($event_id);
							?>
								<li class="option"><a href="/events/<?=$event->slug?>" class="option"><?=$event->getName()?></a></li>
							<?
						}
					}
					?>
            	</ul>
            </div>
            <?
			}
			
			
		
			elapsed('price range');	
			
			#### PRICE RANGE ####
			$event_price = $o->getGA();
			$vars = array(
				'upcoming' => true,
				'status' => 'A',
				'market_id' =>  $o->ct_contract->market_id,
				'ct_category_id' => $o->ct_contract->ct_contract_category[0]['ct_category_id'],
				'ticket_price_range' => array(
											'low' => $event_price['price']['price']-10,
											'high' => $event_price['price']['price']+10
											),
				'order_by' => 'venue.name asc'
			);
			$listing = new EventListing($vars);
			$listing->useWebsiteFilter($website)->getList(); 
			if (sizeof($listing->ct_event_ids)>1) {
			?>
            <div class="padded-section">
            	<h3>Events in this Price Range</h3>
                <ul>
                	<? 
					foreach ($listing->ct_event_ids as $event_id) {
						if($o->ct_event_id != $event_id) {
							$event = new ct_event_min($event_id);
							?>
								<li class="option"><a href="/events/<?=$event->slug?>" class="option"><?=$event->getName()?></a></li>
							<?
						}
					}
					?>
            	</ul>
            </div>
            <?
			}
			
			

			// restore the refresh value
			$_GET['refresh'] = $temp_refresh;
					
						
						
			elapsed('tags');

			if($o->ct_event_tag[0]) {
			?>
            <div class="padded-section">
                <h3>Tags</h3>
                <ul class="tags">
                	<li class="option">
					<? 
                    foreach ($o->ct_event_tag as $tag) {
                        ?>
                            <a style="display:inline"; href="/tag/<?=urlencode($tag['name'])?>" class="option"><?=$tag['name']?></a>
                        <?
                    }
                    ?>
                	</li>
                </ul>
            </div>
            <?
			}

			elapsed('done');
?>
		</div>
	</div>
</div>
