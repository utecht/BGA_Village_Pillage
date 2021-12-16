<?php
namespace VP\Managers;

use VP\Core\Game;
use VP\Helpers\UserException;
use VP\Managers\Players;
use VP\Notifications\PlayCard;

/**
 * Cards: id, value, color
 *  pId is stored as second part of the location, eg : table_2322020
 */
class Cards extends \VP\Helpers\Pieces {
	protected static $table = 'cards';
	protected static $prefix = 'card_';
	protected static $customFields = ['name', 'color'];
	protected static $autoreshuffle = false;
	protected static function cast($card) {
		$locations = explode('_', $card['location']);
		$class_parts = explode('_', $card['name']);
		$class_name = '';
		foreach ($class_parts as $part) {
			$class_name .= ucfirst($part);
		}
		$class_name = "\VP\Cards\\$class_name";
		return new $class_name([
			'id' => $card['id'],
			'location' => $locations[0],
			'name' => $card['name'],
			'color' => $card['color'],
			'pId' => $locations[1] ?? null,
		]);
	}

	public static function play($pId, $side, $cardId) {
		$card = self::get($cardId);
		$player = Players::get($pId);
		if ($card->getPId() != $pId) {
			throw new UserException("Attempt to play card you do not own");
		}
		self::moveAllInLocation([$side, $pId], ['hand', $pId]);
		self::move($cardId, [$side, $pId]);
		$card->setLocation($side);
		PlayCard::playCard($player, $card);
		if (self::countInLocation(['hand', '%']) == (Players::count() * 2)) {
			Game::get()->gamestate->nextState("done");
		}
	}

	//////////////////////////////////
	//////////////////////////////////
	//////////// GETTERS //////////////
	//////////////////////////////////
	//////////////////////////////////

	/**
	 * getOfPlayer: return the cards in the hand of given player
	 */
	public static function getOfPlayer($pId) {
		return self::getInLocation(['hand', $pId]);
	}

	public static function getPlayerLeft($pId) {
		return self::getTopOf(['left', $pId]);
	}

	public static function getPlayerRight($pId) {
		return self::getTopOf(['right', $pId]);
	}

	public static function refreshHands($players) {
		foreach ($players as $player) {
			$pId = $player->getId();
			self::moveAllInLocation(['exhausted', $pId], ['hand', $pId]);
			self::moveAllInLocation(['left', $pId], ['exhausted', $pId], CARD_EXHAUSTED);
			self::moveAllInLocation(['right', $pId], ['exhausted', $pId], CARD_EXHAUSTED);
			self::moveAllInLocation(['left', $pId], ['hand', $pId]);
			self::moveAllInLocation(['right', $pId], ['hand', $pId]);
		}
	}

	//////////////////////////////////
	//////////////////////////////////
	///////////// SETTERS //////////////
	//////////////////////////////////
	//////////////////////////////////

	/**
	 * setupNewGame: create the deck of cards
	 */
	public function setupNewGame($players, $options) {

		foreach ($players as $pId => $player) {
			$cards = [];
			$cards[] = ['name' => 'farmer', 'color' => CARD_GREEN];
			$cards[] = ['name' => 'raider', 'color' => CARD_RED];
			$cards[] = ['name' => 'wall', 'color' => CARD_BLUE];
			$cards[] = ['name' => 'merchant', 'color' => CARD_YELLOW];
			self::create($cards, ['hand', $pId]);
		}
	}
}
