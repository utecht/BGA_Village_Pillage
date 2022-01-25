<?php
namespace VP\Cards;
use VP\Managers\Cards;
use VP\Models\Card;
use VP\Notifications\BuyRelic;
use VP\Notifications\Gain;

class Doctor extends Card {
	public function buy(&$player, $opposing_card, &$opposing_player) {
		$did_buy = $player->buyRelic(0);
		if ($did_buy == false) {
			$player->income(2);
			$player->updateIncome();
			Gain::gain($player, $this, 2);
			Cards::setState($opposing_card->id, CARD_EXHAUSTED);
		} else {
			BuyRelic::buyRelic($player, $this, $did_buy);
		}
		return false;
	}

}