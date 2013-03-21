<?
global $ct_promoter_id, $website;

// GET THE APPROPRIATE DATA FOR THE SEARCHBOX
$categories = array(
    4, /*'Live Music',*/
    5, /*'Nightlife'*/
    12, /*'Holiday Party Guide'*/
    1,  /*'New Years Eve'*/
    9, /*'Bar Crawls'*/
    13, /*'Comedy'*/
    14, /*'Charity'*/
    15, /*'Festivals'*/
);

switch($ct_category_id) {
    default: // New Years Eve
        $sb = array();
        $where = array();
        $where[] = "ct_event.status in ('A','C','S','B')";
//      $where[] = "ct_contract_date.start_date > now() - interval '1 day'";
        $clause = array(
            'venue_type' => array(
                'where'=>$where
            )
        );
// GET THE VENUE TYPES FOR THE EVENTS AND ASSIGN THE COUNT FOR EACH
        $aql = " distinct venue_type { id, slug, name_plural } venue {} ct_event {} ct_contract {}";
        $v_t_ids = aql::select($aql,$clause);
        $a_count=0;
        foreach ($v_t_ids as $v_t_id) {
            $sb['Venue Type'][$a_count]['slug'] = $v_t_id['slug'];
            $sb['Venue Type'][$a_count]['name'] = $v_t_id['name_plural'];
            $a = array(
                'seller__ct_promoter_id'=> $crave_ct_promoter_id,
                'ct_category_id'=>$ct_category_id,
                'market_id'=>$market_id,
                'upcoming'=>true,
                'status'=>'A',
                'venue_type_id'=>$v_t_id['id']
            );
            $events = ct_event::getList($a);
            $count=count($events);
            $sb['Venue Type'][$a_count]['count'] = $count;
            $a_count++;
        }

// PRICE RANGE FOR NEW YEARS WITH COUNT
/*
        $names = array(
            'Less than $100' => 'under-100',
            '$101 - $150' => '101-150',
            '$151 - $200' => '151-200',
            '$201 - $250' => '201-250',
            'More than $250' => 'over-250'
        );

        $a_count = 0;
        foreach ($names as $name => $slug) {
            $sb['Price Range'][$a_count]['name']=$name;
            $sb['Price Range'][$a_count]['slug']=$slug;
            $a_count++;
        }

        $values = array(
            0  =>100,
            100=>150,
            151=>200,
            201=>250,
            250=>1000
        );
        $a_count = 0;
        foreach($values as $min => $max) {
            $a = array(
                'seller__ct_promoter_id'=>$ct_promoter_id,
                'ct_category_id'=>$ct_category_id,
                'market_id'=>$market_id,
                'upcoming'=>true,
                'status'=>'A',
                'ticket_price_range' => array('low' => $min, 'high' => $max)
            );
            $events = ct_event::getList($a);
            $sb['Price Range'][$a_count]['count'] = count($events);
            $a_count++;
        }

// NEIGHBORHOODS WITH COUNT
        $hoods = aql::select("market_nbhd { id, name, slug where market_id = {$market_id} order by name asc }");
        $a_count=0;
        foreach($hoods as $hood) {
            $sb['Neighborhoods'][$a_count]['name'] = $hood['name'];
            $sb['Neighborhoods'][$a_count]['slug'] = $hood['slug'];
            $a = array(
                'seller__ct_promoter_id'=>$ct_promoter_id,
                'ct_category_id'=>$ct_category_id,
                'market_id'=>$market_id,
                'upcoming'=>true,
                'status'=>'A',
                'where'=>'market_nbhd.id = '.$hood['id']
            );
            $events = ct_event::getList($a);
            $sb['Neighborhoods'][$a_count]['count'] = count($events);
            $a_count++;
        }
    break; // case: 1
*/
}    // switch


?>
<div id="searchbox">
    <div id="searchbox-top"></div>
    <div id="searchbox-name" class="univers">EVENT</div>
    <div id="the-word-search" class="univers gradient2">SEARCH</div>
    <div id="searchbox-results<?=!is_array($selected_filters)?'-hidden':''?>">
        <div id="result-text">Showing results for:</div>
        <div id="search-result-container">
            <div id="search-result"></div>
            <div id="x-result"><a id="remove-result">X</a></div>
        </div>
        <div id="clear-all"><a id="clear">Clear All&nbsp;&nbsp;&nbsp;X</a></div>
    </div>
    <div id="refine-search">Refine this Search</div>
<?
    if (is_array($sb)) {
        foreach ($sb as $search_cat => $arr1) {
?>
            <div>
                <div class="search-category"><?=$search_cat?></div>
                <div class="search-items">
                    <ul>
<?
                    $y = 0;
                    foreach ($arr1 as $key => $arr2) {
                        if ($arr2['count'] > 0) {
                            $y++;
?>
                            <li <? if ($y > 1) { ?>class="search-item-has-top-border"<? } ?>>
                                <a href="/<?=$arr2['slug']?>" class="search-item"><?=$arr2['name']?> (<?=$arr2['count']?>)</a>
                            </li>
<?
                        }
                    } /*
?>
                <li class="search-item-has-top-border">
                            <a class="search-item" id="more-genres">Show More</a>
                </li>
    <?
        // GET THE GENRES THAT ARE NOT IN config.php's $genre_search_items array
                    $rs = aql::select("music_genre { name where name not in ('".implode("','",$genre_search_items)."') order by name ASC }");
                    foreach ($rs as $r) {
                        $count = rand(1,20);
    ?>
                        <li class="hidden-genre-search-item">
                            <a class="search-item"><?=$r['name']?> (<?=$count?>)</a>
                        </li>
    <?
                    } */
?>

                    </ul>
                </div>
            </div>
<?
        }
    }
?>

    <div id="search-bottom"></div>
</div>