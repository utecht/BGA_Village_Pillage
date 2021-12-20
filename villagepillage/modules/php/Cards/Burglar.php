<?php
namespace VP\Cards;
use VP\Models\Card;

class Burglar extends Card {
	public function steal(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_GREEN || $opposing_card->color == CARD_YELLOW) {
			// TODO: implement super steal
			$opposing_player->steal($player, 4, $this);
		}
	}
}