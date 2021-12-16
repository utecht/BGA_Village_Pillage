<?php
namespace VP\States;

use VP\Core\Game;
use VP\Managers\Players;

trait WallsTrait {
	function stWalls() {
		$players = Players::getAll();
		Game::doActions(CARD_BLUE, $players);
		Game::get()->gamestate->nextState("done");
	}
}
