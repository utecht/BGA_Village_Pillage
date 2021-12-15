<?php
namespace VP\Notifications;
use VP\Core\Game;
use VP\Core\Notifications;
use VP\Managers\Cards;
use VP\Managers\Players;

class PlayCard extends \VP\Core\Notifications {
	public static function playCard($player, $card) {
		self::notify($player, 'playMyCard', 'You played ${card_name} to ${card_location}', [
			"player" => $player,
			"card" => $card,
		]);
		$card_location = $card->getLocation();
		self::notifyAll('playCard', '${player_name} played to ${card_location}', [
			"player" => $player,
			"card_location" => $card_location,
		]);
		if (Cards::countPlayed() == (Players::count() * 2)) {
			Game::get()->gamestate->nextState("done");
		}
	}
}