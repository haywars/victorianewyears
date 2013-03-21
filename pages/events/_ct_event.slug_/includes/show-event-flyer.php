<? 
	$flyer_file = IDE;
	$flyer = vf::getItem($flyer_file,800);
?>	

<style type="text/css">
	.skybox-image {
		position:relative;
	}
	.skybox-image .image img {
		float:left;
	}
	.skybox-image .back-button {
		position:absolute;
		top:-10px;
		right:-10px;
		background-color:#fff;
	}
</style>

<div class="skybox-image" style="">
	<a class="image" href="javascript:history.back();"><?=$flyer->img?></a>
	<a href="javascript:history.back();" class="back-button"><img src="/images/close-x.gif" /></a>
</div>