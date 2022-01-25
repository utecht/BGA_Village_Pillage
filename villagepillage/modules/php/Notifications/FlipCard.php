<?php
namespace VP\Notifications;
use VP\Core\Notifications;

class FlipCard extends \VP\Core\Notifications {
	public static function flipCard($card) {
		self::notifyAll('flipCard', '${card_name} added to market', [
			"card" => $card,
		]);
	}
}