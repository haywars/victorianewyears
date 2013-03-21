<?php

if ($website->redirect) {
    #redirect($website->redirect);
}

if (!$website->default_market || !$website->default_category) {
    $this->inherit('pages/no-events');
    return;
}

if ($website->getField('splash_enabled') && $website->getField('splash__media_item_id')) {
    $this->inherit('pages/splash');
    return;
}

if (!$website->getField('show_all_events_on_homepage')) {

    # redirect if website's sitemap does not have a homepage
    $formats = array('/%s', '/%s/%s');
    $type_url = array(
        'markets' => array('market'),
        'categories' => array('market'),
        'holidays' => array('category'),
        'markets-categories' => array('market', 'category')
    );

    $type = $type_url[$website->getField('sitemap_type')];
    if ($type) {
        $format = $formats[count($type) - 1];
        $pars = array_map(function($r) use($website) {
            return $website->{'default_' . $r};
        }, $type);
        $this->redirect(vsprintf($format, $pars));
    }

    # no redirect -- we are showing something on the homepage
    if ( $website->getField('sitemap_type') == 'single-listing' ) {
        $p->vars['market_id'] = $website->getFirstMarket('market_id');
        $p->vars['market_slug'] = $website->getFirstMarket('market_slug');
        $p->vars['ct_category_id'] = $website->getFirstCategory('ct_category_id');
        $p->vars['ct_category_slug'] = $website->getFirstCategory('ct_category_slug');
    }

} else {
    $h1 = $website->title;
}

# markets/ categories are unnecessary because this is either single listing
# or showing allevents or producer?
$event_criteria = array(
    'upcoming' => true,
    'status' => 'A'
);

include 'includes/events-listing.php';
