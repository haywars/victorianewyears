<?php

global $disk_cache_vf_slidedhow;

$profile_main_width = 640;

elapsed('before getting folder venue pics');
$photos_vfolder_path = "/venues/{$o->venue_id}";
$vfolder = vf::getFolder($photos_vfolder_path);
elapsed('after getting folder venue pics');

$event_times = $o->getEventTimes();

$href = $_SERVER['SERVER_NAME'].$p->uri;

$dir = 'pages/events/_ct_event.slug_/layouts/';
$nye1 = $dir.'nye1/';
$inc = function($a) use($nye1) { return $nye1.$a.'.php'; };

// flyer position
$profile->flyer_position = 'side';
foreach ( $o->ct_contract->ct_contract_category as $category ) {
    if ( $category['category_slug'] == 'thanksgivingeve' ) {
        $profile->flyer_position = 'main';
    }
}

?>
<div id="profile-content" class="ebrt">
    <div id="left-content">


<?

    include $inc('event-header');

    ps(array(
        'title' => '',
        'body' => function() use($event) {
?>
                <ul id="tickets_container">
<?
                foreach ($event->tickets as $t) {
?>
                    <li>
                        <div class="ticket-name">
                            <a href="#ticket_<?=$t['ct_ticket_ide']?>"
                                class="quick_jump"
                                >
                                <?=$t['name']?>
                            </a>
                        </div>
<?
                        section::ticketbox($t, $event);
?>
                    </li>
<?
                }
?>
                </ul>
                <p><strong>Prices Subject To Change</strong></p>
<?
        }
    ));

    if ( $profile->flyer_position == 'main' ) {
        ps(array(
            'title' => '',
            'body' => function() use($o,$profile) {
                // flyer
                $flyers = $profile->getFlyers(); // uses website->ct_promoter_id
                if (is_array($flyers))
                foreach ( $flyers as $flyer_info ) {
                    $flyer_id = $flyer_info['_id'];
                    $flyer = vf::getItem($flyer_id, 640);
                    if ($flyer->html) {
?>
                        <img src="<?=$flyer->src?>" width="<?=$flyer->width?>" />
<?
                        break;
                    }
                }
            }
        ));
    }


    include $inc('venue-walkthrough');
    include $inc('youtube-url');

    include $inc('event-details');

?>

    </div>
    <div id="side-content">
        <div>
<?
            include $inc('social-media');

            if ( $profile->flyer_position == 'side' ) {
                // flyer
                $flyers = $profile->getFlyers(); // uses website->ct_promoter_id
                if (is_array($flyers))
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

            // if websitesidebar stuff
            if ($website->sidebar_html) {
                ?><div id="website_sidebar_html">
                    <?=$website->sidebar_html?>
                </div><?
            }

?>

            <div class="when-where">
                <div>
<?
                    $def_door_time = date('g:i a', strtotime($o->ct_contract->door_time));
                    if ($o->ct_contract['event_start_time'])
                        $event_start_time = date('g:i a', strtotime($o->ct_contract->event_start_time));
                    if ($o->ct_contract->close_time)
                        $def_close_time = date('g:i a', strtotime($o->ct_contract->close_time));
                    foreach ($o->ct_contract->dates as $d) {
                        if (!strtotime($d['start_date'])) continue;
                        $date = date('l, F jS Y', strtotime($d['start_date']));
                        $door_time = ($d['start_time']) ? date('g:i a', strtotime($d['start_time'])) : $def_door_time;
                        $close_time = ($d['end_time']) ? date('g:i a', strtotime($d['end_time'])) : $def_close_time;
                        if ($event_start_time) {
                            $timestring = 'Doors Open: '.$door_time.' | '.$event_start_time;
                        } else {
                            $timestring = $door_time;
                        }
                        if ($close_time) $timestring .= ' - '.$close_time;
?>
                            <div id="ww_date"><?=$date?></div>
                            <div id="ww_time"><?=str_replace(':00 ','',$timestring)?></div>
<?
                        }
?>
                </div>
            </div>
            <div class="when-where">
                <div id="ww_name"><?=$o->venue->venue_name?></div>
                <div id="ww_address"><?=$o->venue->address1?></div>
                <div id="ww_city"><?=$o->venue->city?> <?=$o->venue->state?> <?=$o->venue->zip?></div>
<?
            if ($o->venue->latitude && $o->venue->longitude) {
?>
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
<?
            }
?>

            </div>
<?

            $cache_name = 'venue:photos:sidebar:'.$o->ct_event_ide;
            $photos = function() use($vfolder) {
                elapsed('before venue photos');
                ps(array(
                    'title' => '',
                    'body' => function() use($vfolder) {
                        echo vf::slideshow(array(
                            'folder' => $vfolder,
                            'width' => 300,
                            'height' => 200,
                            'limit' => 6,
                            'thumb_width' => 94,
                            'thumb_height' => 70
                        ))->html;
                    }
                ));
                elapsed('after venue photos');
            };

            if ($vfolder->items) {
                if ($disk_cache_vf_slidedhow) {
                    while ($p->cache($cache_name, $disk_cache_vf_slidedhow)) {
                        $photos();
                    }
                } else {
                    $photos();
                }
            }

            elapsed('before getting folder party pics');
            if($o->is_halloween) {
                $party_pics_path = "/venues/{$o->venue_id}/halloween_party_pics";
                $party_pics_vfolder = vf::getFolder($party_pics_path);
            } else {
                $party_pics_path = "/venues/{$o->venue_id}/nye_party_pics";
                $party_pics_vfolder = vf::getFolder($party_pics_path);
            }
            elapsed('afer getting folder party pics');

            $cache_name = 'venue:photos:paty-pics-sidebar:' . $o->getIDE();
            $party_pics = function() use($party_pics_vfolder) {
                elapsed('before party pics');
                ps(array(
                    'title' => 'Party Pics',
                    'body' => function() use($party_pics_vfolder) {
                        echo vf::slideshow(array(
                            'folder' => $party_pics_vfolder,
                            'width' => 300,
                            'height' => 200,
                            'limit' => 6,
                            'thumb_width' => 94,
                            'thumb_height' => 70
                        ))->html;
                    }
                ));
                elapsed('after party pics');
            };

            if ($party_pics_vfolder->items) {
                if ($disk_cache_vf_slidedhow) {
                    while ($p->cache($cache_name, $disk_cache_vf_slidedhow)) {
                        $party_pics();
                    }
                } else {
                    $party_pics();
                }
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
