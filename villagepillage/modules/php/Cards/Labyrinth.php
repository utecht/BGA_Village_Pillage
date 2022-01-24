<?php
namespace VP\Cards;
use VP\Managers\Cards;
use VP\Models\Card;
use VP\Notifications\Bank;
use VP\Notifications\Gain;
use VP\Notifications\Steal;

class Labyrinth extends Card {
	public function gain(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color != CARD_RED) {
			$player->income(1);
			Gain::gain($player, $this, 1);
		}
	}

	public function steal(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_RED) {
			$opposing_player->steal($player, 3, $this);
			Cards::setState($opposing_card->id, CARD_EXHAUSTED);
		}
	}

	public function bank(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color != CARD_RED) {
			$player->bank(2);
			Bank::bank($player, $this, 2);
		}
	}
}