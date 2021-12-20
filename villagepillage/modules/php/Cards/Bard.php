<?php
namespace VP\Cards;
use VP\Managers\Cards;
use VP\Models\Card;
use VP\Notifications\BuyRelic;
use VP\Notifications\Gain;
use VP\Notifications\GainCard;

class Bard extends Card {
	public function buy(&$player, $opposing_card, &$opposing_player) {
		$did_buy = $player->buyRelic(0);
		if ($did_buy === false) {
			Cards::pickOneForLocation(['deck'], ['hand', $player->id]);
			GainCard::gainCard($player, $this);
			$player->income(1);
			$player->updateIncome();
			Gain::gain($player, $this, 1);
		} else {
			BuyRelic::buyRelic($player, $this, $did_buy);
		}
		return false;
	}

}