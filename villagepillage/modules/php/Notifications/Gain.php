<?php
namespace VP\Notifications;
use VP\Core\Notifications;

class Gain extends \VP\Core\Notifications {
	public static function gain($player, $card, $amount) {
		self::notifyAll('gain', '${player_name} gained ${amount} with ${card_name}', [
			"player" => $player,
			"amount" => $amount,
			"card" => $card,
		]);
	}
}