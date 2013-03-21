<?php
$vfolder_path = '/venues/' . $venue->getID();
$vfolder = vf::getFolder($vfolder_path);
if (sizeof($vfolder->items) == 0) {
    return;
}
?>
<div class = "grid_item">
    <h3 class = "univers">VENUE PHOTOS</h3>
    <div class = "grid_item_content">
<?php
    echo vf::slideshow(array(
        'folder' => $vfolder,
        'width' => 313,
        'height' => 260,
        'limit' => 8,
        'thumb_width' => 72,
        'thumb_height' => 54
    ))->html;
?>
    </div>
</div>