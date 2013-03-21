<?php
$a = array(
    'seller__ct_promoter_id' => CT_PROMOTER_ID,
    'upcoming' => true,
    'status' => 'A',
    'venue_id' => $venue->getID(),
    'order_by' => 'ct_contract_date.start_date asc'
);
$events = ct_event::getList($a);

if($events) {
?>
<div class = "grid_item">
    <h3 class = "univers">UPCOMING EVENTS</h3>
    <div class = "grid_item_content">
<?php
    foreach ($events as $event) {
        $event = new \Crave\Api\Event( array(
            'event' => $event,
            'seller' => CT_PROMOTER_ID,
            'website' => $website
        ));
        $flyer = $event->getFlyers();
        if ($flyer) {
            $img_id = $flyer[0]['_id'];
        } else {
            $venue_img_array = $event->getEvent()->getMediaIDs(1);
            if ($venue_img_array) {
                $img_id = $venue_img_array[0];
            }
        }
        if ($img_id) {
            $img = vf::getItem($img_id, array('width' => 80));
            unset($img_id);
        }
?>
        <div class="venue_event">
<?php
        if ($img) {
?>
            <a class="event_img" href="<?=$event->url?>"><?=$img->html?></a>
<?php
        }
?>
            <div class="event_title"><a href="<?=$event->url?>"><?=$event->seo['h1']?></a></div>
            <div class="event_date"><?=date('M jS, Y',$event->when['date']['U'])?> - <?=date('g:i a',$event->when['door_time']['U'])?></div>
            <div class="address"><?=$event->seo['address']?></div>
            <div class="subtitle"><?=$event->seo['subtitle']?></div>
            <a class="button" href="<?=$event->url?>">Tickets</a>
        </div>
<?php
        unset($img);
    }
?>
    </div>
</div>
<?
}