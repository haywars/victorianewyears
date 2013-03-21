<div id="photo_collage" class="has_floats">
<?
if ($venue_ids) {
	foreach ($venue_ids as $venue_id) {
		$item = vf::getRandomItem('/venues/'.$venue_id.'/logo', 90,90,NULL );
		$top_pad = floor((90 - $item->height)/2);
		$bottom_pad  = 90 - $item->height - $top_pad;
		
		if($item->src) { ?>
		
			<a href="/events/<?=$event->slug?>" style="width:90px;padding-top:<?=$top_pad?>px;padding-bottom:<?=$bottom_pad?>px;background-color:#fff;" class="rounded">
			<img src="<?=$item->src?>" height="<?=$item->height?>" class="rounded" width="<?=$item->width?>" />
			</a>
		<? }
	}
}
?>
</div>