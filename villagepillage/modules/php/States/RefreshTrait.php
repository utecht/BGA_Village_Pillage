<?php
namespace VP\States;

use VP\Core\Game;
use VP\Core\Notifications;
use VP\Managers\Cards;
use VP\Managers\Players;
use VP\Notifications\Refresh;

trait RefreshTrait {
	function stRefresh() {
		Notifications::message("Refresh");
		$players = Players::getAll();
		Cards::refreshHands($players);
		Refresh::refresh();
		Game::get()->gamestate->nextState("next");
	}
}
