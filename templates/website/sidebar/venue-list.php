<?
$vars = array(
	'upcoming' => true,
	'status' => 'A',
	'order_by' => 'venue.name asc'
);

global $website, $p;

$listing = new EventListing($vars);
$listing->usePageFilter($p)
	->useWebsiteFilter($website)
	->getList();

?>
<ul id="venue_list" class="color_1_border color_1_text">
<?
foreach ($listing->ct_event_ids as $event) {
    $event = new ct_event($event);
    if($event->venue->latitude && $event->venue->longitude) {
        $venue_ids[$event->venue->venue_id] = $event->venue->venue_id;
        ?>
        <li><a href="/events/<?=$event->slug?>"><?=$event->venue->venue_name?> <span><?=str_replace(' ','&nbsp',$event->venue->name_modifier)?></span></a></li>
        <?
    }
}
?>
</ul>