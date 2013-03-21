<? 
$p->js[] = '/pages/_market.slug_/map/map.js';
$p->css[] = '/pages/_market.slug_/map/map.css';

if (sizeof($website->markets) > 1) {
	redirect('/'.$website->markets[0]['market_slug'].'/map');
} elseif (sizeof($website->markets) == 1) {

} else {
	redirect('/');
}

include 'pages/_market.slug_/map/map.php';