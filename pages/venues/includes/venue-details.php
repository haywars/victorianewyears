<div class = "grid_item">
    <h3 class = "univers">VENUE DETAILS</h3>
    <div class = "grid_item_content">
        <div class = "grid_list_item" style="font-size:14px;">
            <div class = "grid_list_title">Address</div>
            <div class="address"><?=$venue->address1?></div>
            <div><?=$venue->city.', '.$venue->state.' '.$venue->zip?></div>
<?php
        if ($venue->phone) {
?>
            <div><?=format_phone($phone)?></div>
<?php
        }
        if ($venue->website){
?>
            <a href="http://<?=$venue['website']?>" target="_blank">
                <?=str_replace(array('www.','http://','https://'),'',$venue->website)?>
            </a>
<?php
        }
?>
        </div>
<?php
    if ($venue->venue_type_name) {
?>
        <div class = "grid_list_title">
            Venue Type
        </div>
        <div class = "grid_list_item">
            <?=$venue->venue_type_name?>
        </div>
<?php
    }
?>
    </div>
</div>