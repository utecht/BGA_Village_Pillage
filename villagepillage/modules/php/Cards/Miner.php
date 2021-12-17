<?php
namespace VP\Cards;
use VP\Managers\Cards;
use VP\Models\Card;
use VP\Notifications\Gain;

class Miner extends Card {
	public function gain(&$player, $opposing_card, &$opposing_player) {
		$to_gain = 4;
		if ($opposing_card->color == CARD_BLUE) {
			$to_gain = 5;
			Cards::setState($opposing_card->id, CARD_EXHAUSTED);
		}
		$player->income($to_gain);
		Gain::gain($player, $this, $to_gain);
	}
}