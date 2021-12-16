<?php
namespace VP\Cards;
use VP\Models\Card;
use VP\Notifications\BuyRelic;

class Merchant extends Card {
	public function buy(&$player, $opposing_card, &$opposing_player) {
		$did_buy = $player->buyRelic(0);
		if ($did_buy === false) {
			// TODO: buy card
		} else {
			BuyRelic::buyRelic($player, $this, $did_buy);
		}
	}
}