<?php
namespace VP\Cards;
use VP\Models\Card;

class Farmer extends Card {
	public function farm(&$player, $opposing_card, &$opposing_player) {
		$player->income(4);
	}
}