<?php
namespace VP\Notifications;
use VP\Core\Notifications;

class Refresh extends \VP\Core\Notifications {
	public static function refresh() {
		self::notifyAll('refresh', 'All cards refreshed', [
		]);
	}
}