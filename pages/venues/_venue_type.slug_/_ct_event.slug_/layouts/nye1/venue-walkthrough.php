<?

// venue walkthrough

if ($o->venue->walkthrough_youtube_url) {
	ps(array(
		'title' => '',
		'body' => function() use($o, $profile_main_width) {
			snippet::youtube_embed($o->venue->walkthrough_youtube_url, $profile_main_width);
		}
	));
}