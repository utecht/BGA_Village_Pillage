<?php
namespace VP\States;

use VP\Core\Game;
use VP\Core\Notifications;
use VP\Managers\Cards;
use VP\Managers\Players;
use VP\Notifications\Refresh;

trait MerchantsTrait {
	function stMerchants() {
		Notifications::message("Running Merchants");
		$players = Players::getAll();
		Game::doActions(CARD_YELLOW, $players);
		Cards::refreshHands($players);
		Refresh::refresh();
		Game::get()->gamestate->nextState("done");
	}
}
