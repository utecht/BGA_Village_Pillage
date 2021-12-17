<?php
namespace VP\Cards;
use VP\Models\Card;
use VP\Notifications\Bank;
use VP\Notifications\Gain;
use VP\Notifications\Steal;

class Treasury extends Card {
	public function gain(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color != CARD_RED) {
			$player->income(1);
			Gain::gain($player, $this, 1);
		}
	}

	public function steal(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_RED) {
			$opposing_player->steal($player, 2);
			Steal::steal($player, $this, 2, $opposing_player);
		}
	}

	public function bank(&$player, $opposing_card, &$opposing_player) {
		$player->bank(4);
		Bank::bank($player, $this, 4);
	}
}