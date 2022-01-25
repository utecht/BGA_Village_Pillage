<?php
namespace VP\Cards;
use VP\Models\Card;
use VP\Notifications\Bank;
use VP\Notifications\Gain;

class Pickler extends Card {
	public function gain(&$player, $opposing_card, &$opposing_player) {
		$player->income(4);
		Gain::gain($player, $this, 4);
	}

	public function bank(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color != CARD_RED) {
			$banked = $player->bank(2);
			Bank::bank($player, $this, $banked);
		}
	}
}