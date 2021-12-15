<?php
namespace VP\States;

use VP\Core\Game;
use VP\Managers\Players;

trait FarmersTrait {
	function stFarmers() {
		$players = Players::getAll();
		Game::processTurn('farm', $players);
		foreach ($players as $player) {
			$player->updateIncome();
		}
		Game::get()->gamestate->nextState("done");
	}
}
