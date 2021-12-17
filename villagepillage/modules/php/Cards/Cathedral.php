<?php
namespace VP\Cards;
use VP\Models\Card;
use VP\Notifications\Bank;
use VP\Notifications\Steal;

class Cathedral extends Card {

	public $buyPrice = 1;

	public function steal(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_RED) {
			$opposing_player->steal($player, 3);
			Steal::steal($player, $this, 3, $opposing_player);
		}
	}

	public function bank(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_RED) {
			$player->bank(1);
			Bank::bank($player, $this, 1);
		}
	}

	public function buy(&$player, $opposing_card, &$opposing_player) {
		return true;
	}
}