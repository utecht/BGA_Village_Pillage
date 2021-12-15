<?php
namespace VP\States;

use VP\Core\Game;
use VP\Managers\Players;

trait WallsTrait {
	function stWalls() {
		$players = Players::getAll();
		Game::processTurn('wall', $players);
		foreach ($players as $player) {
			$player->updateIncome();
		}
		foreach ($players as $player) {
			$player->payThieves();
		}
		foreach ($players as $player) {
			$player->updateIncome();
		}
		foreach ($players as $player) {
			$player->updateBank();
		}
		Game::get()->gamestate->nextState("done");
	}
}
