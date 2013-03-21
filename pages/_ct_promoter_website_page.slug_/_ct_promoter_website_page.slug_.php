<?
$website_page = ct_promoter_website_page::getByClause(array(
    'where' => array(
        "ct_promoter_website_id = {$website->ct_promoter_website_id}",
        "slug ilike '{$p->vars['ct_promoter_website_page_slug']}'"
    ),
    'limit' => 1
));

if(!$website_page) {
    include('pages/404.php');
    return;
}

$p->template('website', 'top');
?>

<h1><?=$p->seo['h1']?></h1>
<div id="h1_blurb"><?=$p->seo['h1_blurb']?></div>

<?
echo $website_page->html;

$p->template('website', 'bottom');	