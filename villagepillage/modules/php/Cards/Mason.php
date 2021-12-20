<?php
namespace VP\Cards;
use VP\Models\Card;
use VP\Notifications\Bank;
use VP\Notifications\Gain;

class Mason extends Card {
	public function gain(&$player, $opposing_card, &$opposing_player) {
		$player->income(4);
		Gain::gain($player, $this, 4);
	}

	public function steal(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_BLUE) {
			$opposing_player->steal($player, 1, $this);
		}
	}

	public function bank(&$player, $opposing_card, &$opposing_player) {
		$player->bank(2);
		Bank::bank($player, $this, 2);
	}
}