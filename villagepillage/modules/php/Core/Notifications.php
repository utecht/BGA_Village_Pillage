<?php
namespace VP\Core;

class Notifications {
	/*************************
		   **** GENERIC METHODS ****
	*/
	protected static function notifyAll($name, $msg, $data) {
		self::updateArgs($data);
		Game::get()->notifyAllPlayers($name, $msg, $data);
	}

	protected static function notify($player, $name, $msg, $data) {
		$pId = is_int($player) ? $player : $player->getId();
		self::updateArgs($data);
		Game::get()->notifyPlayer($pId, $name, $msg, $data);
	}

	public static function message($txt, $args = []) {
		self::notifyAll('message', $txt, $args);
	}

	public static function messageTo($player, $txt, $args = []) {
		$pId = is_int($player) ? $player : $player->getId();
		self::notify($pId, 'message', $txt, $args);
	}

	/*********************
		   **** UPDATE ARGS ****
	*/
	/*
		   * Automatically adds some standard field about player and/or card
	*/
	protected static function updateArgs(&$args) {
		if (isset($args['player'])) {
			$args['player_name'] = $args['player']->getName();
			$args['player_id'] = $args['player']->getId();
			$args['player'] = $args['player']->tokenSerialize();
		}

		if (isset($args['card'])) {
			$args['card'] = $args['card']->jsonSerialize();
			$args['card_name'] = $args['card']['name'];
			$args['card_side'] = $args['card']['side'];
		}

		// if (isset($args['task'])) {
		//   $c = $args['task'];
		//   $args['task_desc'] = $c->getText();
		//   $args['i18n'][] = 'task_desc';
		//
		//   if (isset($args['player_id'])) {
		//     $args['task'] = $args['task']->jsonSerialize($args['task']->getPId() == $args['player_id']);
		//   }
		// }
	}
}

?>
