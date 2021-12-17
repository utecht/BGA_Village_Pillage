<?php
namespace VP\Notifications;
use VP\Core\Notifications;

class GainCard extends \VP\Core\Notifications {
	public static function gainCard($player, $card) {
		self::notify($player, 'gainMyCard', 'You drew ${card_name} from deck', [
			"player" => $player,
			"card" => $card,
		]);
		$card_side = $card->getSide();
		self::notifyAll('gainCard', '${player_name} drew top of deck', [
			"player" => $player,
		]);
	}
}