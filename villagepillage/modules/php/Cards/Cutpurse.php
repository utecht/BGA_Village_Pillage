<?php
namespace VP\Cards;
use VP\Models\Card;

class Cutpurse extends Card {
	public function steal(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_YELLOW || $opposing_card->color == CARD_GREEN) {
			$opposing_player->steal($player, 6, $this);
		}
		if ($opposing_card->color == CARD_RED) {
			$player->steal($opposing_player, 1, $this);
		}
	}
}