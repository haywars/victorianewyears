<?php

global $tab, $website, $resellerURL, $myaccount_link, $dev, $seo_website;

if ($template_area == 'top') {

    if ($website->generated_css) {
        $this->head[] = '<style type="text/css">' . $website->generated_css . '</style>';
    }

    switch ( $website->getField('theme') ) {
        case 'dark':
            $page_class = 'dark';
            $this->css[] = '/templates/website/themes/dark/dark.css';
            break;
        default:
            $page_class = 'light';
            $this->css[] = '/templates/website/themes/light/light.css';
    }

    $this->head[] = '<script type="text/javascript" src="http://beta.cravetickets.com/toolbar/embed.js?brand=crave"></script>';

    $this->template('html5', 'top');

    $top_nav = array(
        '/' => 'Home',
        $myaccount_link => 'My Account',
        'http://cravetickets.com/faq' => 'FAQ',
        'http://cravetickets.com/feedback' => 'Customer Service',
        '/track-order' => 'Track Order'
    );

?>
    <div id="container" class="has-floats">
        <?   if (auth("admin:seo; admin:developer")) {
                        $seo_style = 'cursor:pointer; '
                                   . 'float:left; '
                                   . 'margin-top:2px; '
                                   . 'color: #40b3d9; '
                                   . 'text-decoration: none; '
                                   . 'font-size: 10px;';

?>
                        <li>
                            <a  id="seo"
                                style="<?=$seo_style?>"
                                page_path="<?=$this->page_path?>"
                                website_ide="m2ahUMxLOGf"
                                uri="<?=$this->uri?>"
                                >
                                SEO
                            </a>
                        </li>
<?
        }
?>
        <header class="has-floats">
            <div class="has-floats" id="top-section">
                <div class="float-left" id="headline">
                    <?=$website->headline?>
                </div>
               
                    <ul>
<?
                    foreach ($top_nav as $uri => $name) {
?>
                       <!-- <li>
                            <a href="<?=$uri?>"><?=$name?></a>
                        </li>-->
<?
                    }
                 
?>
                    </ul>
               
            </div>
            <div id="header_area">
<?
            
            switch ($website->getField('logo_type')) {
                case 2:
                    if ( $logo_id = $website->getField('logo__media_item_id') ) {
                        $i = vf::getItem($logo_id);
                        if ( $i->width > 960 ) $i = vf::getItem( $logo_id , 960 );
                        if ( $i->html ) {
?>
                            <a href="/" style="overflow:hidden"><?=$i->html?></a>
<?
                        }
                    }
                    break;
                case 3: // No Logo
                    break;
                case 4:
                    echo $website->getField('logo_text');
                    break;
                case 5:
                    if ( $website->getField('header_html') ) {
?>
                        <?=$website->getField('header_html')?>
<? 
                    }
                    break;
                default:
?>
                    <a href="/" id="logo_text" class="univers">
                        <?=$website->actual_logo_text?>
                    </a>
<?
                    break;
            }
?>
            </div>
           <aside>
            <div id="search_box" class="has-floats">
                <form method="GET" action="/search-results" name="search_form">
                    <input id="search_field" name="q" value="<?=$_GET['q']?>" placeholder="  Search Events" class="float-left" />
                    <a href="#" id="search_button" class="float-left">&raquo;</a>
                </form>
            </div>                
                <ul>
                    <li><a href="http://www.facebook.com/NewYearsEve">Facebook</a></li>
                    <li><a href="http://twitter.com/newyearsparties">Twitter</a></li>
                    <li><?=$website->promo_phone;?></li>
                </ul>
                <div class="countdown">
<?
                $year = date('Y') + 1;
                $month = 1;
                $day = 1;
                $countdown_to_prefix = 'NY';
                $countdown_to_title = "Countdown to New Years";
?>
                <p class="top"><?=$countdown_to_title?></p>
                <p class="counter">
                    <span id="<?=$countdown_to_prefix?>_D1">0</span>
                    <span id="<?=$countdown_to_prefix?>_D2">0</span>
                    <span id="<?=$countdown_to_prefix?>_D3"class="gap">0</span>
                    <span id="<?=$countdown_to_prefix?>_H1">0</span>
                    <span id="<?=$countdown_to_prefix?>_H2" class="gap">0</span>
                    <span id="<?=$countdown_to_prefix?>_M1">0</span>
                    <span id="<?=$countdown_to_prefix?>_M2" class="gap">0</span>
                    <span id="<?=$countdown_to_prefix?>_S1">0</span>
                    <span id="<?=$countdown_to_prefix?>_S2">0</span>
                </p>
                <p class="legend">
                    <span class="day">day</span>
                    <span class="hr">hr</span>
                    <span class="min">min</span>
                    <span class="sec">sec</span>
                </p>
                <script type="text/javascript">
                    $(document).ready(function() {
                        // initiate countdown clock
                        setInterval('countdown(<?=$year?>,<?=$month?>, <?=$day?>, 0, "<?=$countdown_to_prefix?>_")', 1000);
                    });
                </script>
            </div>
            <a href="http://beta.cravetickets.com/sell-tickets/get-started?ref=<?=$website->domain?>" target="_blank">Post a Party</a>
            </aside>
            
            <nav id="main-nav" class="has-floats">
<?          $tab = ($_POST['tab']) ?: $p->tab;
            $nav = array(
                'events' => array(
                    'url' => '/',
                    'title' => 'Events'
                ),
                'venues' => array(
                    'url' => '/venues',
                    'title' => 'Venues'
                ),
                'map' => array(
                    'url' => '/map',
                    'title' => 'Party Map'
                ),
                /*'nightlife' => array(
                    'url' => '/nightlife',
                    'title' => 'Nightlife'
                ),*/
            );
            /*$nav = array(
                'Events' => '',
                'Venues' => 'venues',
                'Map' => 'map',
                'Nightlife' => 'nightlife'
            );*/
            if (count($nav)) {
                $main_text = ($website->getField('show_all_events_on_homepage') &&
                              $this->urlpath = '/')
                           ? 'All Cities'
                           : $this->vars['market_name'];
                           
?>
            <ul class="has-floats">
            
<?
                foreach ($nav as $t => $params) {
                 if ($params['hide']) continue;
?>
                <li<?=($tab == $t)?' class="selected"':''?>>
                    <a href="<?=$params['url']?>" class="univers"><?=$params['title']?></a>
                </li>
<?
            } // end foreach nav
?>
            </ul>
<?
        }
?>
        </nav>
        </header>

        <div id="main" class="has-floats">
            <div id="page" class="<?=$page_class?>">
            
<?
        //krumo($website);
} else if ($template_area == 'bottom') {

    $signup_uri = sprintf(
        'http://beta.cravetickets.com/signup/reseller?ref_promoter=%s&ref_website=%s',
        $website->ct_promoter_ide,
        $website->getIDE()
    );

?>
            </div>
        </div>
        <footer class="has-floats">
            <div class="has-floats">
                <div class="float-left">
                    <p>
                        &copy; <?=date('Y')?> -
                        Powered By <a href="http://cravetickets.com">CraveTickets.com</a>.
                        All rights reserved.
                    </p>
                    <p>
                        <strong>
                            <a href="<?=$signup_uri?>">Get Your Own Ticketing Website</a> |
                            <a href="<?=$myaccount_link?>">Reseller Login</a>
                        </strong>
                    </p>
                </div>
                <div class="float-right">
                    <p>
                        <strong>
                            <a href="/terms-and-conditions">Terms of Service</a> |
                            <a href="/privacy-policy">Privacy Policy</a>
                        </strong>
                    </p>
                    <p>
<?
                        global $sky_start_time;
                        $sky_end_time = microtime(true);
                        $elapsed_time = $sky_end_time - $sky_start_time;
                        echo round($elapsed_time, 1) . 's';
?>
                    </p>
                </div>
            </div>
<?
            if ($_GET['reseller_debug']) {
                echo '<div>';
                krumo($resellerURL);
                echo '</div>';
            }
?>
        </footer>
    </div>
    <div style="display:none;">
<?
        if ($_GET['vf_refresh']) print_pre( vf::$client );
?>
    </div>
    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-1583693-9']);
        _gaq.push(['_setDomainName', 'none']);
        _gaq.push(['_setAllowLinker', true]);
        _gaq.push(['_trackPageview']);
        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script>

<?
    $this->template('html5', 'bottom');
}
