<?php
namespace VP\States;

use VP\Core\Game;
use VP\Managers\Players;

trait RaidersTrait {
	function stRaiders() {
		$players = Players::getAll();
		Game::doActions(CARD_RED, $players);
		Game::get()->gamestate->nextState("done");
	}
}
