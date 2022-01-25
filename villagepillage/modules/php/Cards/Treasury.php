<?php
namespace VP\Cards;
use VP\Models\Card;
use VP\Notifications\Bank;
use VP\Notifications\Gain;

class Treasury extends Card {
	public function gain(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color != CARD_RED) {
			$player->income(1);
			Gain::gain($player, $this, 1);
		}
	}

	public function steal(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_RED) {
			$opposing_player->steal($player, 2, $this);
		}
	}

	public function bank(&$player, $opposing_card, &$opposing_player) {
		$banked = $player->bank(4);
		Bank::bank($player, $this, $banked);
	}
}