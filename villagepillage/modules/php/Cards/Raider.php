<?php
namespace VP\Cards;
use VP\Models\Card;

class Raider extends Card {
	public function steal(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_GREEN || $opposing_card->color == CARD_YELLOW) {
			$opposing_player->steal($player, 4, $this);
		}
	}
}