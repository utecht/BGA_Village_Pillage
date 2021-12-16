<?php
namespace VP\Cards;
use VP\Models\Card;
use VP\Notifications\Gain;

class Farmer extends Card {
	public function gain(&$player, $opposing_card, &$opposing_player) {
		$player->income(3);
		Gain::gain($player, $this, 3);
	}
}