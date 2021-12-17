<?php
namespace VP\Cards;
use VP\Models\Card;
use VP\Notifications\Bank;
use VP\Notifications\Gain;
use VP\Notifications\Steal;

class Moat extends Card {
	public function gain(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_RED) {
			$player->income(2);
			Gain::gain($player, $this, 2);
		}
		if ($opposing_card->color == CARD_GREEN) {
			$opposing_player->income(1);
			Gain::gain($opposing_player, $this, 2);
		}
	}

	public function steal(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_RED) {
			$opposing_player->steal($player, 3);
			Steal::steal($player, $this, 3, $opposing_player);
		}
	}

	public function bank(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_BLUE || $opposing_card->color == CARD_YELLOW) {
			$player->bank(2);
			Bank::bank($player, $this, 2);
		}
	}
}