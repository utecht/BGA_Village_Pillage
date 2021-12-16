<?php
namespace VP\Notifications;
use VP\Core\Notifications;
use VP\Managers\Cards;

class Reveal extends \VP\Core\Notifications {
	public static function reveal() {
		self::notifyAll('reveal', 'All cards revealed', [
			'cards' => array_merge(
				Cards::getInLocation(['left', '%'])->toArray(),
				Cards::getInLocation(['right', '%'])->toArray()
			),
		]);
	}
}