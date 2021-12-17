<?php
namespace VP\Cards;
use VP\Models\Card;
use VP\Notifications\Steal;

class Berserker extends Card {
	public function steal(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_YELLOW || $opposing_card->color == CARD_GREEN) {
			$opposing_player->steal($player, 6);
			Steal::steal($player, $this, 6, $opposing_player);
		}
		if ($opposing_card->color == CARD_BLUE) {
			$player->steal($opposing_player, 1);
			Steal::steal($opposing_player, $this, 1, $player);
		}
	}
}