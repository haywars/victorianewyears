<?php

// too slow
return;

global $p, $website;

$vars = array(
    'status' => 'A',
    'upcoming' => true,
    'order by' => 'random()',
    'limit' => 3
);

$listing = new EventListing($vars);
$listing->usePageFilter($p)
    ->useWebsiteFilter($website)
    ->getList();

foreach($listing->ct_event_ids as $id) {
    
    $folder = sprintf('/ct_event/%s/banners', $id);
    $pars = array(
        'search' => array(
            'height' => 250,
            'width' => 300
        )
    );

    elapsed('before getitem');
    $item = vf::getRandomItem($folder, $pars);
    elapsed('after getiem');
    
    if(!$item->html) {
        // krumo($item);
        continue;
    }

    $event = new event(ct_event::getPartial($id), $website);
?>
    <a href="<?=$event->url?>"><?=$item->html?></a>
<?
    break;

}
