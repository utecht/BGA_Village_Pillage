<?php
namespace VP\Cards;
use VP\Models\Card;
use VP\Notifications\Gain;

class Innkeeper extends Card {

	public $buyPrice = 0;

	public function gain(&$player, $opposing_card, &$opposing_player) {
		$to_gain = 4;
		if ($opposing_card->color == CARD_YELLOW) {
			$to_gain = 5;
		}
		$player->income($to_gain);
		Gain::gain($player, $this, $to_gain);
	}

	public function buy(&$player, $opposing_card, &$opposing_player) {
		return true;
	}
}