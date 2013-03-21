<?php
if ($venue->foursquare_id) {
    global $foursquare_api_key, $foursquare_api_secret;
    $num_of_tips = 10;

    $mem_key = "venues:tips:".$venue->getID();
    $foursq_data = mem($mem_key);
    if (!$foursq_data) {
        $foursq_data = array();
        $url = 'https://api.foursquare.com/v2/venues/'.$venue->foursquare_id.'?ll='.$venue->latitude.','.$venue->longitude.'&client_id='.$foursquare_api_key.'&client_secret='.$foursquare_api_secret;
        $json = GetCurlPage($url);
        $json = json_decode($json, true);
        $json  = $json['response']['venue']['tips']['groups'][0]['items'];
        for ($i=0;$i<$num_of_tips;$i++) {
            if ($json[$i]) {
                $foursq_data[] = array(
                    'photo_url' => $json[$i]['user']['photo'],
                    'name' => $json[$i]['user']['firstName'].' '.$json[$i]['user']['lastName'],
                    'date' =>date('n/j/y',$json[$i]['createdAt']),
                    'tip' => $json[$i]['text']
                );
            }
        }
        mem($mem_key, $foursq_data, $json);
    }

    if ($foursq_data) {
?>
        <div class="grid_item">
            <h3 class="univers tips" style="background-color:#0CBADF;">TIPS</h3>
<?php
        foreach ($foursq_data as $tip) {
?>
            <div class="tip">
                <div class="pic">
                    <img src="<?=$tip['photo_url']?>" />
                </div>
                <div class="text">
                    <div class="name">
                        <?=$tip['name']?>
                        <span class="date"><?=$tip['date']?></span>
                    </div>
                    <?=$tip['tip']?>
                </div>
            </div>
<?php
        }
?>
        </div>
<?php
    }
}