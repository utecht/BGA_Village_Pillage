<?php
namespace VP\States;

use VP\Core\Game;
use VP\Managers\Cards;
use VP\Managers\Players;

trait MerchantsTrait {
	function stMerchants() {
		$players = Players::getAll();
		Game::processTurn('merchant', $players);
		foreach ($players as $player) {
			$player->updateIncome();
		}
		foreach ($players as $player) {
			$pId = $player->id;
			Cards::moveAllInLocation(['left', $pId], ['hand', $pId]);
			Cards::moveAllInLocation(['right', $pId], ['hand', $pId]);
		}
		Game::get()->gamestate->nextState("done");
	}
}
