<?php
namespace VP\Notifications;
use VP\Core\Notifications;

class PlayCard extends \VP\Core\Notifications {
	public static function playCard($player, $card) {
		self::notify($player, 'playMyCard', 'You played ${card_name} to ${card_location}', [
			"player" => $player,
			"card" => $card,
		]);
		$card_location = $card['location'];
		self::notifyAll('playCard', '${player_name} played to ${card_location}', [
			"player" => $player,
			"card_location" => $card_location,
		]);
	}

}