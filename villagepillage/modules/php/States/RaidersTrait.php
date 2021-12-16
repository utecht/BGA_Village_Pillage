<?php
namespace VP\States;

use VP\Core\Game;
use VP\Core\Notifications;
use VP\Managers\Players;

trait RaidersTrait {
	function stRaiders() {
		Notifications::message("Running Raiders");
		$players = Players::getAll();
		Game::doActions(CARD_RED, $players);
		Game::get()->gamestate->nextState("done");
	}
}
