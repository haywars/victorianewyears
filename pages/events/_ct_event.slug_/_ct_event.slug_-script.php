<?


class section {


	public static function clean($string) {

		$clears = 'font-size|margin-top|margin-bottom|font-family';
		$pattern = array_map(function($r) {
			return sprintf('/%s:[\w"\'\s]*;?/is', $r);
		}, explode('|', $clears));

		return preg_replace($pattern, '', $string);
	}

	public static function qj($title, $fn = null, $fn2 = null) { ?>
		<div class="has-floats" name="qj_<?=$title?>">
			<div class="has-floats">
				<div class="float-right">
					<a href="#totop" class="back_to_top">Back To Top</a>
				</div>
<?
				if (is_callable($fn2)) $fn2();
?>
				<h4>
					<?=ucwords(preg_replace('/_/', ' ', $title))?>
				</h4>
			</div>
			<div class="qj_content has-floats">
<?
				if (is_callable($fn) && !Model::isModelClass($fn)) {
					$fn();
				} else {
					 echo strip_inline_style($fn->preparedData[$title]);
				}
?>
			</div>
		</div>
<?
	}

	public static function ticketbox($ticket, $event, $text = 'Buy Now') {

		$o = $event->metadata['ct_event']->ct_contract;

		$text = ($text) ?: 'Buy Now';

		// notify text for if event is announced
		$get_notify_text = function() use($o) {
			if (!$o->sales_start_time) return 'soon.';
			$time = strtotime($o->sales_start_time);
			return 'on ' . date('M. jS', $time) . ' at ' . date('g:ia', $time);
		};

		if ($ticket['notify_me']) {
?>
			<div class="float-right tickets_announced">
				Tickets will go on sale <?=$get_notify_text()?>
			</div>
<?
		} else if ($ticket['no_button']) {
?>
			<div class="float-right">
				<span class="red"><?=$ticket['display_text']?></span>
			</div>
<?
		} else {
?>
			<div class="ticket-buy-container">
				<div class="float-left ticket-price-container">
					<div class="ticket-price">
						<?=$ticket['display_text']?>
					</div>
					<div class="small gray service-charge">
						+<?=number_format($ticket['fee'],2)?> Fee
					</div>
				</div>
				<div class="float-right">
					<a href="<?=$event->buy_url?>" class="buy-button">
						<?=$text?>
					</a>
				</div>
			</div>
<?
		}

	} // end ticketbox

}


// profile seciton function
// arr -> title, sidebar(bool), no_cufon(bool)
// body is a closure
function ps($arr) {

	$arr['no_cufon'] = true;
	$id = ($arr['id']) ? 'id="' . $arr['id'] . '"' : '';

?>
	<div class="section has-floats" <?=$id?>>
		<div class="section-content has-floats">
<?
			if (is_callable($arr['body'])) {
				$arr['body']();
			} else {
				echo $arr['body'];
			}
?>
		</div>
<?
		if (!$arr['sidebar'] && !$arr['hide_back_to_top']) {
?>
			<a href="#" class="back_to_top">Back To Top</a>
<?
		}
?>
	</div>
<?
}
