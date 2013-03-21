<?php

$event_cache_minutes = 5; // range between this and this + 5 minutes
$left_nav_cache_duration = '2 hours'; // only if the # of events is the same

$this->page_sidebar = 'no_sidebar';

$this->css[] = '/lib/layouts/right-sidebar/right-sidebar.css';
$this->js[] = '/lib/layouts/right-sidebar/right-sidebar.js';
$this->html_attrs['xmlns:fb'] = 'http://www.facebook.com/2008/fbml';


$all_events = ct_event::getList($eventCriteriaAllDates);

$mem_key = 'date_array'
    . ':' . $website->ct_promoter_website_id
    . ':' . $this->urlpath
    . ':' . count($all_events);

$date_array = mem($mem_key);
if (!$date_array) {

    $date_array = array();
    //krumo($all_events);

    foreach ($all_events as $ct_event_id) {
        $event_min = new ct_event_min($ct_event_id);
        //krumo($event_min());
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
    ksort($date_array);
    mem($mem_key, $date_array, $left_nav_cache_duration);
}


if (count($date_array) > 1) {

    if ($this->urlpath == '/') {
        $single_listing = true;
    }

    // make sure the date in the url is valid
    foreach ($date_array as $value) {
        if ($this->queryfolders[0] == $value['date'] || $_GET['date'] == $value['date']) {
            $valid_date = true;
        }
    }
    // if it's not, redirect to the date with the most events
    if ( (!$this->queryfolders[0] && !$_GET['date']) || !$valid_date) {
        $max_events = 0;
        foreach ($date_array as $key => $date) {
            //echo $date['count'] . ' > ' . $max_events . '<br />';
            if ($date['count'] > $max_events) {
                $max_events = $date['count'];
                $redirect_date = $date['date'];
                //echo $redirect_date;
            }
        }
        if ($single_listing) {
            $redirect = '?date=' . $redirect_date;
        } else {
            $redirect = $this->urlpath . '/' . $redirect_date;
        }
        redirect($redirect);
    }
}


$this->template('website','top');
?>
<div class="listing">

        <div id="left-nav">
<?
            include 'left-nav.php';
?>
        </div>

        <style type="text/css">
            html .m {
                padding:4px;
            }
        </style>

        <div id="event-listings">

<?

        $grid = new array_pagination($events,array(
            'default_limit' => 50,
            'limits' => array(10,50,100)
        ));

        if ($grid->rs) {
?>
            <h1><?=$this->seo['h1']?$this->seo['h1']:$this->title?></h1>
            <div id="h1_blurb"><?=$this->seo['h1_blurb']?></div>

            <div id="fb-root"></div>

<?
            if ($website->getField('layout_top_banner')){
?>
                <div id="layout_top_banner"><?=$website->getField('layout_top_banner')?></div>
<?
            }

            if(sizeof($date_array) > 1) {
?>
                <div class="date_tabs">
<?
                foreach ($date_array as $key => $d) {
                    if ($single_listing) {
                        $date_href = '?date='.$d['date'];
                    } else {
                        $date_href = $this->urlpath.'/'.$d['date'];
                    }

                    if ($d['date'] == $this->queryfolders[0] || $d['date'] == $_GET['date']) {
                        $selected = true;
                    } else {
                        $selected = false;
                    }
?>
                    <div <?=$selected?'class="selected"':''?>>
                        <a href="<?=$date_href?>"><?=date('l  n/j',$key)?></a>
                        <?=$selected?'<div class="triangle"><div></div></div>':''?>
                    </div>
<?
                }
?>
                </div>
<?
            }


?>

            <div class="pagination-showing">
<?
                if ($grid->num_pages > 1) {
                    $grid->showing();
                }
?>
                <!-- PRE-LOAD THE HOVER STATE FOR THE BUY BUTTON TO PREVENT FLICKERING -->
                <img src="/lib/layouts/left-nav-standard/images/button-tile-hover.jpg" style="display:none;" />
            </div>

<?


            elapsed('-- begin Event loop --');

            foreach ( $grid->rs as $i => $ct_event_id ) {

                $cache_name = 'website:'
                    . $website->ct_promoter_website_id
                    . "/layout-type:left-nav-standard/event_id:"
                    . $ct_event_id;

                elapsed('before event cache');

                $event_cache_duration = rand($event_cache_minutes, $event_cache_minutes + 5);

                while ($this->cache($cache_name, $event_cache_duration . ' minutes')) {

                    $event = new \Crave\Api\Event(array(
                        'id' => $ct_event_id,
                        'website' => $website,
                        'seller' => $event_criteria['seller__ct_promoter_id'],
                        'min' => true
                    ));

                    if ($website->eventIsFeatured($ct_event_id)) {
                        elapsed('event is featured');
                        include 'featured-event.php';
                    } else {
                        elapsed('event is not featured');
                        include 'basic-event.php';
                    }
                }

                elapsed('after event cache');
            }

            if($this->seo['footer_blurb']) {
?>
                <div id="footer_blurb"><?=$this->seo['footer_blurb']?></div>
<?
            }

            if ($grid->num_pages > 1) {
                $grid->pages();
            }
        } else {
?>
            <p>There are no events for this criteria. Check Back Soon.</p>
<?
        }
?>

        </div>
</div> <!-- end .listing -->

<?

elapsed('end left-nav-standard');

$this->template('website','bottom');
