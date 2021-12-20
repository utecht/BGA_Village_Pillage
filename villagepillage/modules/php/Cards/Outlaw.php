<?php
namespace VP\Cards;
use VP\Models\Card;

class Outlaw extends Card {

	public $buyPrice = 0;

	public function steal(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_GREEN) {
			$opposing_player->steal($player, 5, $this);
		}
		if ($opposing_card->color == CARD_YELLOW) {
			$opposing_player->steal($player, 4, $this);
		}
	}
	public function buy(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_YELLOW) {
			return true;
		}
	}
}