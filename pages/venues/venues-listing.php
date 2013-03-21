<?php
//echo 'success';
$market = new market($this->vars['market_id']);
$this->title = 'Crave Tickets - '.$market_name.' Venues';
$this->tab = 'venues';
$this->breadcrumb = array(
    'Home' => '/',
    $market->name.' Venues' => null
);
$event_cache_duration = '20 minutes';
$a = array(
    'all' => true,
    'upcoming'=> true,
    'status'=>'A',
    'limit' => 4,
    'featured' => true,
    'order by' => 'random()'
);
$this->template('website','top');

if($this->queryfolders[0]) {
    $params = array('where' => array("venue_type.slug ilike '".$this->queryfolders[0]."'"));
    $venue_type = venue_type::getList($params);
}

$rs = aql::select("market_nbhd { name where market_id = {$market_id} }");

// SEARCHBOX 
//include 'includes/searchbox.php';
?>
<aside class="venue-left-nav">
    <?
include 'includes/left-nav.php';
?>
</aside>
<?
//include 'includes/sidebar.php';
// RIGHT SIDE
// SET MEM KEY
$mem_key = "venues:list:".$_SESSION['market_id'];
if ($venue_type) {
    $mem_key .= ":".$this->queryfolders[0];
} else {
    $mem_key .= ":all";
}

$venues = mem($mem_key);
if (!$venues) {
    $a = array(
        'market_id'=>$_SESSION['market_id'],
        'limit'=> 20,
        'order_by' => 'pageviews desc'
    );
    if ($venue_type) {
        $a['venue_type_id'] = $venue_type[0];
    }
    $venues = venue::getList($a);
    mem($mem_key,$venues,'1 day');
}

include 'includes/grid.php';
include 'includes/skyscraper-sidebar.php';

$this->template('website','bottom');