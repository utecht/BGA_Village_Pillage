<?php
namespace VP\Cards;
use VP\Models\Card;

class Wall extends Card {
	public function wall(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_RED) {
			$opposing_player->steal($player, 1);
			$player->bank(1);
		} else {
			$player->bank(1);
			$player->income(1);
		}
	}
}