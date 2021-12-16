<?php
namespace VP\Notifications;
use VP\Core\Notifications;

class BuyRelic extends \VP\Core\Notifications {
	public static function buyRelic($player, $card, $amount) {
		self::notifyAll('buyRelic', '${player_name} bought relic for ${amount} with ${card_name}', [
			"player" => $player,
			"amount" => $amount,
			"card" => $card,
		]);
	}
}