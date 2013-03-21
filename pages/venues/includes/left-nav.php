<?
global  $p, $website, $price_ranges;
?>
<div id="left-nav-inner" class="test">
    <div class="fb-like-box" data-href="http://www.facebook.com/NewYearsEve" data-width="170" data-height="102" data-show-faces="false" data-stream="false" data-header="true"></div>
   <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=280223158746041";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <!--<div id="search_box" class="has-floats">
        <form method="GET" action="/search-results" name="search_form">
            <input id="search_field" name="q" value="<?=$_GET['q']?>" placeholder="  Search Events" class="float-left" />
            <a href="#" id="search_button" class="float-left">&raquo;</a>
        </form>
    </div>-->
    <div class="picker">
<?
    if (sizeof($website->markets) > 1) {
        $side_markets = $website->getSideMarkets(10);
?>
        <h4>Pick a City</h4>
        <ul class="cities">
<?
      
        foreach ($side_markets as $k => $m) {
?>
                <li><a href="/<?=$m['market_slug']?>" class="<?=$this->vars['market_id']==$m['market_id']?'current':''?>">
                    <?=$m['market_name']?>
                </a></li>
<?

        }
?>
        </ul>
<?
        if (count($website->markets) > 10) {
?>
        <ul class="cities_more">
            <li><a id="market_sb_side">More</a></li>
        </ul>
<?
        }
    }

    if (false && $price_ranges) {
?>
        <h4 class="side-nav-item do_toggle" toggle="price"><a href="javascript:void(null);">Price Range</a></h4>
        <ul id="price">
            <? foreach ($price_ranges as $k => $v) : ?>
            <li><a href="/price-range/<?=$v?>"><?=$k?></a></li>
            <? endforeach; ?>
        </ul>
<?
    }
    if(!$date_array) {
        $date_array = $p->vars['date_array'];
    }

    if (is_array($date_array))
    foreach ($date_array as $key => $event_day){
        if(is_numeric($key)) {
?>
            <h4>New Years Eve Events:<?//=date('D, F j, Y',$key)?></h4>
            <ul>
<?
            if($event_day['events']) {
                foreach ($event_day['events'] as $side_event) {
?>
                    <li>
                        <a href="<?=$side_event['uri']?>">
<?
                        echo $side_event['name'];
                        if ($side_event['modifier']) {
?>
                            <span class="modifier"><?=$side_event['modifier']?></span>
<?
                        }
?>
                            <span class="side-time"><?=$side_event['time']?></span>
                        </a>
                    </li>
<?
                }
            }
        }
?>
        </ul>
<?
    }

        if ($website->getField('layout_sidebar_skyscraper')) {
?>
            <div id="layout_sidebar_skyscraper">
                <?=$website->getField('layout_sidebar_skyscraper')?>
            </div>
<?
        }
?>
    </div>
</div>
