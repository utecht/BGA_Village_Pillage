<?php
namespace VP\Cards;
use VP\Models\Card;
use VP\Notifications\Gain;
use VP\Notifications\Steal;

class Trapper extends Card {
	public function gain(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_GREEN || $opposing_card->color == CARD_YELLOW) {
			$player->income(1);
			Gain::gain($player, $this, 1);
		}
	}

	public function steal(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_GREEN || $opposing_card->color == CARD_YELLOW) {
			$opposing_player->steal($player, 4);
			Steal::steal($player, $this, 4, $opposing_player);
		}
		if ($opposing_card->color == CARD_RED) {
			$opposing_player->steal($player, 1);
			Steal::steal($player, $this, 1, $opposing_player);
		}
	}
}