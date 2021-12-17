<?php
namespace VP\Cards;
use VP\Models\Card;
use VP\Notifications\Gain;
use VP\Notifications\Steal;

class Florist extends Card {
	public function gain(&$player, $opposing_card, &$opposing_player) {
		$player->income(5);
		Gain::gain($player, $this, 5);
	}

	public function steal(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_RED) {
			$player->steal($opposing_player, 2);
			Steal::steal($opposing_player, $this, 2, $player);
		}
	}
}