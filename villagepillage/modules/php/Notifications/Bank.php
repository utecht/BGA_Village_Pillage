<?php
namespace VP\Notifications;
use VP\Core\Notifications;

class Bank extends \VP\Core\Notifications {
	public static function bank($player, $card, $amount) {
		self::notifyAll('bank', '${player_name} banked ${amount} with ${card_name}', [
			"player" => $player,
			"amount" => $amount,
			"card" => $card,
		]);
	}
}