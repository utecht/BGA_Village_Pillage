<?php
namespace VP\Cards;
use VP\Models\Card;
use VP\Notifications\BuyRelic;

class Bard extends Card {
	public function buy(&$player, $opposing_card, &$opposing_player) {
		$did_buy = $player->buyRelic(0);
		if ($did_buy === false) {
			// TODO: implement take top of deck and gain 1
			$player->income(1);
			$player->updateIncome();
			Gain::gain($player, $this, 1);
			return true;
		} else {
			BuyRelic::buyRelic($player, $this, $did_buy);
		}
		return false;
	}

}