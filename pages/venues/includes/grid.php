<div id="venues-listing-container">
    <h1 id="venues-listing-header" class="univers">
        <?=$GLOBALS['seo']['h1']?:$market_name.' Venues: '?><?=$o->venue_type_name?:'All'; ?>
    </h1>
    <div id="venues-listing-blurb"><?=$GLOBALS['seo']['h1_blurb']?:$venue_blurb?></div>
    <div id="venues-listing">
<?php
    $total_count = count($venues);
    if (is_array($venues)) {
        foreach ($venues as $key => $venue) {
            $o = new venue($venue);
            $img =  vf::getRandomItem("/venues/".$o->getID(),156,114,true);
            $venue_address = $venue->venue_address;
?>
            <div class="venues-listing-row <?=$key==0?'highlight-y':''?>">
                <div class="venues-listing-col-1">
                    <div class="venues-listing-number add-top-margin">#<?=$key+1?></div>
                    <div class="venues-listing-total">of <?=$total_count?></div>
                </div>
                <div class="venues-listing-col-2">
                    <div class="venues-listing-photo" id="photo_<?=$o->getIDE()?>">
                        <a href="/venues/<?=$o->getIDE()?>">
                            <?=$img->html?$img->html:'<img src="/images/default-156x114.jpg" width="156" height="114" />'?>
                        </a>
                    </div>
                </div>
                <div class="venues-listing-col-3">
                    <div class="venues-listing-buttons-container">
                        <div class="venues-listing-buttons">
                            <a href="/venues/<?=$o->getIDE()?>" style="color:#fff; text-decoration:none;" class="more-info-button">MORE INFO</a>
                        </div>
                    </div>
                    <div class="venues-listing-venue-info">
                        <div class="venues-listing-venue-name"><a href="/venues/<?=$o->getIDE()?>"><?=$o->venue_name?></a></div>
                        <div class="venues-listing-venue-type"><?=$o->venue_type_name?></div>
                    </div>
                    <div class="venues-listing-venue-address"><?=$venue_address?></div>
                    <div class="venues-listing-event-age"></div>
                </div>
            </div>
<?php
        }
    }
?>
    </div>
</div>