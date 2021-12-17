<?php
namespace VP\States;

use VP\Core\Game;
use VP\Core\Globals;
use VP\Core\Notifications;
use VP\Managers\Cards;
use VP\Managers\Players;

trait MerchantsTrait {
	function stMerchants() {
		Notifications::message("Running Merchants");
		$players = Players::getAll();
		$merchants = [];
		$cards = Cards::getInLocation(['play', '%']);
		foreach ($cards as $card_id => $card) {
			if ($card->color == CARD_YELLOW) {
				$merchants[] = $card_id;
			}
		}
		Globals::setMerchants($merchants);
		if (count($merchants) == 0) {
			Game::get()->gamestate->nextState("none");
			return;
		}
		Game::get()->gamestate->nextState("done");
	}

	function stRunMerchant() {
		$merchants = Globals::getMerchants();
		$merchant_id = array_shift($merchants);
		Globals::setMerchants($merchants);
		if ($merchant_id === null) {
			Game::get()->gamestate->nextState("done");
			return;
		}
		Notifications::message("Running merchant " . $merchant_id);
		Game::get()->gamestate->nextState("next");
	}
}
