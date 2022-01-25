<?php
namespace VP\Cards;
use VP\Models\Card;
use VP\Notifications\Bank;

class Cathedral extends Card {

	public $buyPrice = 1;

	public function steal(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_RED) {
			$opposing_player->steal($player, 3, $this);
		}
	}

	public function bank(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_RED) {
			$banked = $player->bank(1);
			Bank::bank($player, $this, $banked);
		}
	}

	public function buy(&$player, $opposing_card, &$opposing_player) {
		return true;
	}
}