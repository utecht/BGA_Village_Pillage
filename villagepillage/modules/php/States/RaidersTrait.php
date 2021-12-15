<?php
namespace VP\States;

use VP\Core\Game;
use VP\Managers\Players;

trait RaidersTrait {
	function stRaiders() {
		$players = Players::getAll();
		Game::processTurn('raid', $players);
		foreach ($players as $player) {
			$player->payThieves();
		}
		foreach ($players as $player) {
			$player->updateIncome();
		}
		Game::get()->gamestate->nextState("done");
	}
}
