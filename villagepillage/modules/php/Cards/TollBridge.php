<?php
namespace VP\Cards;
use VP\Models\Card;
use VP\Notifications\Bank;

class TollBridge extends Card {

	public function steal(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_YELLOW || $opposing_card->color == CARD_RED) {
			$opposing_player->steal($player, 2, $this);
		}
	}

	public function bank(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_GREEN || $opposing_card->color == CARD_BLUE) {
			$banked = $player->bank(2);
			Bank::bank($player, $this, $banked);
		}
	}

	public function bankSteal() {
		return true;
	}
}