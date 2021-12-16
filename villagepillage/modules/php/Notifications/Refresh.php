<?php
namespace VP\Notifications;
use VP\Core\Notifications;
use VP\Managers\Cards;

class Refresh extends \VP\Core\Notifications {
	public static function refresh() {
		self::notifyAll('refresh', 'All cards refreshed', [
			'exhausted' => Cards::getInLocation(['exhausted', '%']),
		]);
	}
}