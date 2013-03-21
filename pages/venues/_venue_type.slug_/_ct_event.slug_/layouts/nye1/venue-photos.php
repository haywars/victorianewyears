<?php

global $disk_cache_vf_slidedhow;

// Load performances
$items = array();
if ($o->artists) {
    foreach($o->artists as $artist) {
        elapsed('before artist getFolder');
        $artist_vfolder = vf::getFolder('/artist/' . $artist->artist_id . '/profile');
        elapsed('after artist getFolder');
        if (sizeof($artist_vfolder->items) > 0) {
            foreach($artist_vfolder->items as $itm) {
                $items[]['_id'] = $itm['_id'];
            }
        }
    }
}

if (count($vfolder->items) > 0 &&
    (!$items || $o->is_nye || $o->is_halloween)
) {

    $venue_photos = function() use($vfolder, $profile_main_width) {
        elapsed('before venue-photos slideshow');
        ps(array(
            'title' => '',
            'body' => function() use($vfolder, $profile_main_width) {
                echo vf::slideshow(array(
                    'folder' => $vfolder,
                    'width' => $profile_main_width,
                    'height' => 374,
                    'limit' => 8,
                    'thumb_width' => 61,
                    'thumb_height' => 50
                ))->html;
            }
        ));
        elapsed('after venue-photos slideshow');
    };

    $name = sprintf('event-profile:%s:venue-photos:%s', $o->getID(), $profile_main_width);
    if ($disk_cache_vf_slidedhow) {
        while ($p->cache($name, $disk_cache_vf_slidedhow)) {
            $venue_photos();
        }
    } else {
        $venue_photos();
    }

}




if ($items) {

    $artist_photos = function() use($items, $profile_main_width) {
        elaspsed('before artist gallery');
        ps(array(
            'title' => '',
            'body' => function() use($items, $profile_main_width) {
                echo vf::slideshow(array(
                    'items' => $items,
                    'width' => $profile_main_width,
                    'height' => 374,
                    'limit' => 8,
                    'thumb_width' => 61,
                    'thumb_height' => 50
                ))->html;
            }
        ));
        elaspsed('after artist gallery');
    };

    $name = sprintf('event-profile:%s:artist-photos:%s', $o->getID(), $profile_main_width);
    if ($disk_cache_vf_slidedhow) {
        while ($p->cache($anme, $disk_cache_vf_slidedhow)) {
            $artist_photos();
        }
    } else {
        $artist_photos();
    }

}
