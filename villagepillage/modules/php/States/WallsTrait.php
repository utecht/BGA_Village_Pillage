<?php
namespace VP\States;

use VP\Core\Game;
use VP\Core\Notifications;
use VP\Managers\Players;

trait WallsTrait {
	function stWalls() {
		Notifications::message("Running Walls");
		$players = Players::getAll();
		Game::doActions(CARD_BLUE, $players);
		Game::get()->gamestate->nextState("done");
	}
}
