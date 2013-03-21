<?php
$map_src = 'http://maps.google.com/?q='.$venue->address1.'+'.$venue->city.'+'.$venue->state.'+'.$venue->zip.'&amp;t=p&amp;ie=UTF8&amp;ll=&amp;spn=&amp;z=14&';
?>
<div class = "grid_item">
    <h3 class = "univers">MAP</h3>
    <div class = "grid_item_content">
        <div id = "venue_map">
			<iframe 
				width="313"
				height="200"
				frameborder="0"
				scrolling="no"
				marginheight="0"
				marginwidth="0"
				src="<?=$map_src?>&amp;iwloc=near&addr&amp;output=embed">
			</iframe>
			<a href="<?=$map_src?>&amp;iwloc=addr" target="_blank">View Larger Map</a>
		</div>
    </div>
</div>