<?php
namespace VP\States;

use VP\Core\Game;
use VP\Core\Notifications;
use VP\Managers\Players;
use VP\Notifications\Reveal;

trait FarmersTrait {
	function stFarmers() {
		Reveal::reveal();
		Notifications::message("Running Farmers");
		$players = Players::getAll();
		Game::doActions(CARD_GREEN, $players);
		Game::get()->gamestate->nextState("done");
	}
}
