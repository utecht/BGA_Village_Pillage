<?php
namespace VP\Notifications;
use VP\Core\Notifications;
use VP\Managers\Cards;
use VP\Managers\PlayerTokens;

class Reveal extends \VP\Core\Notifications {
	public static function reveal() {
		self::notifyAll('reveal', 'All cards revealed', [
			'cards' => Cards::getInLocation(['play', '%']),
			'players' => PlayerTokens::getAll(),
		]);
	}
}