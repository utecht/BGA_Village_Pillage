<?php
namespace VP\Notifications;
use VP\Core\Notifications;

class BuyCard extends \VP\Core\Notifications {
	public static function buyCard($player, $card, $amount) {
		self::notifyAll('buyCard', '${player_name} bought ${card_name} for ${amount}', [
			"player" => $player,
			"card" => $card,
			"amount" => $amount,
		]);
	}
}