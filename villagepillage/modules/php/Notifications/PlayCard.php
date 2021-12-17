<?php
namespace VP\Notifications;
use VP\Core\Notifications;

class PlayCard extends \VP\Core\Notifications {
	public static function playCard($player, $card) {
		self::notify($player, 'playMyCard', 'You played ${card_name} to ${card_side}', [
			"player" => $player,
			"card" => $card,
		]);
		$card_side = $card->getSide();
		self::notifyAll('playCard', '${player_name} played to ${card_side}', [
			"player" => $player,
			"card_side" => $card_side,
		]);
	}
}