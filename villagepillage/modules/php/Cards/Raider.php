<?php
namespace VP\Cards;
use VP\Models\Card;
use VP\Notifications\Steal;

class Raider extends Card {
	public function steal(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_GREEN || $opposing_card->color == CARD_YELLOW) {
			$opposing_player->steal($player, 4);
			Steal::steal($player, $this, 4, $opposing_player);
		}
	}
}