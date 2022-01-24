<?php
namespace VP\Notifications;
use VP\Core\Notifications;

class Steal extends \VP\Core\Notifications {
	public static function steal($player, $card, $amount, $target) {
		if ($amount == 0) {
			return;
		}
		self::notifyAll('steal', '${player_name} stole ${amount} from ${player_name2} with ${card_name}', [
			"player" => $player,
			"amount" => $amount,
			"card" => $card,
			"player_name2" => $target->name,
			"target" => $target,
		]);
	}

	public static function stealBank($player, $card, $amount, $target) {
		if ($amount == 0) {
			return;
		}
		self::notifyAll('stealBank', '${player_name} stole ${amount} from ${player_name2} bank with ${card_name}', [
			"player" => $player,
			"amount" => $amount,
			"card" => $card,
			"player_name2" => $target->name,
			"target" => $target,
		]);
	}
}