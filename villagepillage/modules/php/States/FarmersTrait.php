<?php
namespace VP\States;

use VP\Core\Game;
use VP\Managers\Players;
use VP\Notifications\Reveal;

trait FarmersTrait {
	function stFarmers() {
		Reveal::reveal();
		$players = Players::getAll();
		Game::doActions(CARD_GREEN, $players);
		Game::get()->gamestate->nextState("done");
	}
}
