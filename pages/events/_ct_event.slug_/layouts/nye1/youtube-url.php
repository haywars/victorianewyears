<?

// event youtube video
if ($o->youtube_url) {
	ps(array(
		'title' => '',
		'body' => function() use($o, $profile_main_width) {
			snippet::youtube_embed($o->youtube_url, $profile_main_width);
		}
	));
}