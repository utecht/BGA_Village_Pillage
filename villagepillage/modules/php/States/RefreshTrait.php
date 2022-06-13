<?php
namespace VP\States;

use VP\Core\Game;
use VP\Core\Notifications;
use VP\Managers\Cards;
use VP\Managers\Players;
use VP\Notifications\Refresh;

trait RefreshTrait {
	function stRefresh() {
		// check for game end
		$players = Players::getAll();
		$game_ending = false;
		foreach ($players as $player) {
			Game::get()->giveExtraTime($player->getId());
			if ($player->getToken()->relic == RELIC_THREE) {
				$game_ending = true;
			}
		}
		if ($game_ending) {
			Game::get()->gamestate->nextState("end");
			return;
		}
		Notifications::message("Refresh");
		Cards::refreshHands($players);
		Refresh::refresh();
		Game::get()->gamestate->setAllPlayersMultiactive();
		Game::get()->gamestate->nextState("next");
	}
}
