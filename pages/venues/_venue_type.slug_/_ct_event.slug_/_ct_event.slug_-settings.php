<?

if (!$_GET['preview']) {
    $in = "'" . implode("','",ct_event::$active_statuses) . "'";
    $database_folder = array(
        'where' => "ct_event.status in ($in)"
    );
}
