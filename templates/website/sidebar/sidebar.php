<?php

# exit early if 'no_sidebar' || !$sidebar
if ($p->page_sidebar == 'no_sidebar') return;
$sidebar = ($p->page_sidebar) ?: $p->template_sidebar;
if (!$sidebar) return;

# can be set by a file including this one 'sidebar-css-and-js.php'
if (!isset($include_sidebar_assets)) {
    $include_sidebar_assets = false;
}

$getName = function($name, $ext) {
    return sprintf('templates/website/sidebar/%s.%s', $name, $ext);
};

$getFileName = function($n, $is_asset) {
    return ($is_asset) ? '/' . $n : $n;
};

# $ext => $is_asset
$exts = array(
    'css' => true,
    'js' => true,
    'php' => false
);

# filter on if we're including the assets or the php file
$use_exts = array_filter($exts, function($r) use($include_sidebar_assets) {
    return (!$include_sidebar_assets || $r);
});

$sidebar = explode(',', $sidebar);

foreach ($sidebar as $sb) {

    $sb = trim($sb);
    if (!$sb) continue;

    # foreach extension, include it or append it to the page if the file exists
    foreach ($use_exts as $ext => $is_asset) {
        $name = $getName($sb, $ext);
        if (file_exists_incpath($name)) {
            $name = $getFileName($name, $is_asset);
            if ($is_asset) {
                $p->{$ext}[] = $name;
            } else {
                include $name;
            }
        }
    }
}
