<?php
function twitlink ($tweet)
{
    $tweet = preg_replace('/(?<=^|\s)@([a-z0-9_]+)/i','<a href="http://www.twitter.com/$1">@$1</a>',$tweet);
    return $tweet;
}
function twithash ($tweet)
{
    $tweet = preg_replace('/(?<=^|\s)#([a-z0-9_]+)/i','<a href="https://twitter.com/#!/search?q=%23$1">#$1</a>',$tweet);
    return $tweet;
}


if ($venue->twitter) {
    $mem_key = "venue-twitter-".$venue->getID();
    $tweets = mem($mem_key);
    if (!$tweets) {
        $tweets = array();
        $json = GetCurlPage('https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&screen_name='.$venue->twitter.'&count=7&exclude_replys=false');
        $json = json_decode($json, true);
        if (!$json['error']) {
            foreach ($json as $j) {
                $links = array_reverse($j['entities']['urls']);
                foreach ($links as $l) {
                    if ($l['display_url']) {
                        $j['text'] = str_replace(" ".$l['display_url'],' <a href="'.$l['url'].'" rel="nofollow">'.$l['display_url'].'</a>',$j['text']); 
                    } else {
                        $j['text'] = str_replace(" ".$l['url'],' <a href="'.$l['url'].'" rel="nofollow">'.$l['url'].'</a>',$j['text']);
                    }
                }

                if ( strtotime($j['created_at']) > strtotime('-1 day') ) {
                    if (time() - strtotime($j['created_at']) < 3600) {
                        $time = ceil((time() - strtotime($j['created_at']))/60);
                        if ($time == 1) $time.=" minute ago";
                        else $time.=" minutes ago";
                    } else {
                        $time = floor((time() - strtotime($j['created_at']))/3600);
                        if ($time == 1) $time.=" hour ago";
                        else $time.=" hours ago";
                    }
                } else {
                    $time = date('n M',strtotime($j['created_at']));
                }

                $tweets[] = array(
                    'tweet' => twithash(twitlink($j['text'])),
                    'time' => str_replace(" ","&nbsp;",$time)
                );
            }
        }
        mem($mem_key, $tweets,'1 hour');
    }
    if ($tweets) {
?>
    <div class = "grid_item">
        <h3 class = "univers twitter" 
            style=""> </h3>
        <div class = "grid_item_content twitter">
<?php
        foreach ($tweets as $tweet) {
?>
            <div class="tweet"><?=$tweet['tweet']?>
                <span class="tweet_time"><?=$tweet['time']?></span>
            </div>
<?php
        }
?>
        </div>
    </div>
<?php
    }
}
?>

