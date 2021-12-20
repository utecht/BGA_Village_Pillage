<?php
namespace VP\Cards;
use VP\Models\Card;
use VP\Notifications\Bank;
use VP\Notifications\Gain;

class Dungeon extends Card {
	public function gain(&$player, $opposing_card, &$opposing_player) {
		$player->income(1);
		Gain::gain($player, $this, 1);
	}

	public function steal(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_BLUE || $opposing_card->color == CARD_RED) {
			$opposing_player->steal($player, 1, $this);
		}
	}

	public function bank(&$player, $opposing_card, &$opposing_player) {
		$to_bank = 2;
		if ($opposing_card->color == CARD_BLUE || $opposing_card->color == CARD_RED) {
			$to_bank = 1;
		}
		$player->bank($to_bank);
		Bank::bank($player, $this, $to_bank);
	}
}