<?php
namespace VP\Notifications;
use VP\Core\Notifications;

class Poor extends \VP\Core\Notifications {
	public static function poor($player, $amount) {
		self::notifyAll('poor', '${player_name} does not have ${amount} to buy cards', [
			"player" => $player,
			"amount" => $amount,
		]);
	}
}