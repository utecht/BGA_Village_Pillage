<?php
namespace VP\Notifications;
use VP\Core\Notifications;

class Steal extends \VP\Core\Notifications {
	public static function steal($player, $card, $amount, $target) {
		self::notifyAll('steal', '${player_name} stole ${amount} from ${player_name2} with ${card_name}', [
			"player" => $player,
			"amount" => $amount,
			"card" => $card,
			"player_name2" => $target->name,
			"target_id" => $target->getId(),
		]);
	}
}