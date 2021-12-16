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

	public static function doActions($phase, &$players) {
		Game::processTurn($phase, 'gain', $players);
		foreach ($players as $player) {
			$player->updateIncome();
		}
		Game::processTurn($phase, 'steal', $players);
		foreach ($players as $player) {
			$player->payThieves();
		}
		foreach ($players as $player) {
			$player->updateIncome();
		}
		Game::processTurn($phase, 'bank', $players);
		foreach ($players as $player) {
			$player->updateBank();
		}
		Game::processTurn($phase, 'buy', $players);
	}

	public static function processTurn($phase, $turn_type, &$players) {
		$num_players = count($players);
		$i = 0;
		$player_numbers = [];
		foreach ($players as $player) {
			$player_numbers[($player->no - 1)] = $player->id;
		}
		foreach ($players as &$player) {
			$left_card = Cards::getPlayerLeft($player->id);
			if ($left_card->color == $phase) {
				$left_index = Utils::amod(($i - 1), $num_players);
				$left_player = $players[$player_numbers[$left_index]];
				$opposing_left_card = Cards::getPlayerRight($left_player->id);
				$left_card->$turn_type($player, $opposing_left_card, $left_player);
			}
			$right_card = Cards::getPlayerRight($player->id);
			if ($right_card->color == $phase) {
				$right_index = Utils::amod(($i + 1), $num_players);
				$right_player = $players[$player_numbers[$right_index]];
				$opposing_right_card = Cards::getPlayerLeft($right_player->id);
				$right_card->$turn_type($player, $opposing_right_card, $right_player);
			}
			$i++;
		}
	}
}
