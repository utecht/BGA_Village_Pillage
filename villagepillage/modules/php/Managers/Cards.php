<?php
namespace VP\Managers;

use VP\Core\Game;
use VP\Core\Globals;
use VP\Helpers\UserException;
use VP\Managers\Players;
use VP\Notifications\BuyCard;
use VP\Notifications\FlipCard;
use VP\Notifications\PlayCard;
use VP\Notifications\Poor;

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
			'pId' => count($locations) > 1 ? intVal($locations[1]) : null,
			'side' => $locations[2] ?? null,
		]);
	}

	public static function play($pId, $side, $cardId) {
		$card = self::get($cardId);
		$player = Players::get($pId);
		if ($card->getPId() != $pId) {
			throw new UserException("Attempt to play card you do not own");
		}
		if ($card->location == 'exhausted') {
			throw new UserException("You cannot play exhausted cards");
		}
		self::moveAllInLocation(['play', $pId, $side], ['hand', $pId]);
		self::move($cardId, ['play', $pId, $side]);
		$card->setSide($side);
		$card->setLocation('play');
		PlayCard::playCard($player, $card);
		if (self::countInLocation(['play', '%']) == (Players::count() * 2)) {
			Game::get()->gamestate->nextState("done");
		}
	}

	public static function buy($pId, $cardId) {
		$card = self::get($cardId);
		$player = Players::get($pId);
		if ($card->location != 'market') {
			throw new UserException("Attempt to buy card not in market");
		}
		$amount = Globals::getBuyPrice();
		if ($amount > $player->getTurnips()) {
			Poor::poor($player, $amount);
			Game::get()->gamestate->nextState("done");
			return;
		}
		$player->spendTurnips($amount);
		self::move($cardId, ['hand', $pId]);
		$new_card = Cards::pickOneForLocation(['deck'], ['market']);
		BuyCard::buyCard($player, $card, $amount);
		FlipCard::flipCard($new_card);
		Game::get()->gamestate->nextState("done");
	}

	//////////////////////////////////
	//////////////////////////////////
	//////////// GETTERS //////////////
	//////////////////////////////////
	//////////////////////////////////

	/**
	 * getOfPlayer: return the cards in the hand of given player
	 */
	public static function countInPlayOfPlayer($pId) {
		return self::countInLocation(['play', $pId, '%']);
	}

	public static function getOfPlayer($pId) {
		return self::getInLocation(['%', $pId]);
	}

	public static function getOfPlayerExhausted($pId) {
		return self::getInLocation(['exhausted', $pId]);
	}

	public static function getPlayerLeft($pId) {
		return self::getTopOf(['play', $pId, 'left']);
	}

	public static function getPlayerRight($pId) {
		return self::getTopOf(['play', $pId, 'right']);
	}

	public static function refreshHands($players) {
		foreach ($players as $player) {
			$pId = $player->getId();
			self::moveAllInLocation(['exhausted', $pId], ['hand', $pId]);
			self::moveAllInLocation(['play', $pId, '%'], ['exhausted', $pId], CARD_EXHAUSTED);
			self::moveAllInLocation(['play', $pId, '%'], ['hand', $pId]);
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
		self::create(Game::get()->base_cards, ['deck']);
		self::shuffle(['deck']);
		self::pickForLocation(4, ['deck'], ['market']);
	}
}
