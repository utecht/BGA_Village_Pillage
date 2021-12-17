<?php
namespace VP\Cards;
use VP\Managers\Cards;
use VP\Models\Card;
use VP\Notifications\Steal;

class Veteran extends Card {
	public function steal(&$player, $opposing_card, &$opposing_player) {
		if ($opposing_card->color == CARD_GREEN || $opposing_card->color == CARD_YELLOW) {
			$opposing_player->steal($player, 6);
			Steal::steal($player, $this, 6, $opposing_player);
			Cards::setState($this->id, CARD_EXHAUSTED);
		}
	}
}