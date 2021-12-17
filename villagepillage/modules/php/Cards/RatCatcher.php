<?php
namespace VP\Cards;
use VP\Models\Card;
use VP\Notifications\Gain;

class RatCatcher extends Card {
	public function gain(&$player, $opposing_card, &$opposing_player) {
		$to_gain = 4;
		if ($opposing_card->color == CARD_GREEN) {
			$to_gain = 6;
		}
		$player->income($to_gain);
		Gain::gain($player, $this, $to_gain);
	}
}