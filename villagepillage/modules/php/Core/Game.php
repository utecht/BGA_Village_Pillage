<?php
namespace VP\Core;
use villagepillage;
use VP\Helpers\Utils;
use VP\Managers\Cards;

/*
 * Game: a wrapper over table object to allow more generic modules
 */
class Game {
	public static function get() {
		return villagepillage::get();
	}

	public static function processTurn($turn_type, &$players) {
		$num_players = count($players);
		$i = 0;
		$player_numbers = [];
		foreach ($players as $player) {
			$player_numbers[($player->no - 1)] = $player->id;
		}
		foreach ($players as &$player) {
			$left_index = Utils::amod(($i - 1), $num_players);
			$left_player = $players[$player_numbers[$left_index]];
			$right_index = Utils::amod(($i + 1), $num_players);
			$right_player = $players[$player_numbers[$right_index]];
			$left_card = Cards::getPlayerLeft($player->id);
			$opposing_left_card = Cards::getPlayerRight($left_player->id);
			$right_card = Cards::getPlayerRight($player->id);
			$opposing_right_card = Cards::getPlayerLeft($right_player->id);
			$left_card::$turn_type($player, $opposing_left_card, $left_player);
			$right_card::$turn_type($player, $opposing_right_card, $right_player);
			$i++;
		}
	}
}
