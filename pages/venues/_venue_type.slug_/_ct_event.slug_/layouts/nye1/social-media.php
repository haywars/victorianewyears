<div class="bottom-padding" id="social-media">
	<?	
		$href = 'http://'.$website->domain.($o->facebook_url?$o->facebook_url:'/events/'.$o->slug);
		ShareUtil::fb_like(array(
			'href' => $href,
			'layout' => 'box_count',
			'width' => 50,
			'height' => 62
		));
		ShareUtil::twitter_vertical();
		ShareUtil::g_plus(null, $p);
	?>
</div>