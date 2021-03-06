<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * villagepillage implementation : © Joseph Utecht <joseph@utecht.co>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * villagepillage.game.php
 *
 * This is the main file for your game logic.
 *
 * In this PHP file, you are going to defines the rules of the game.
 *
 */

$swdNamespaceAutoload = function ($class) {
	$classParts = explode('\\', $class);
	if ($classParts[0] == 'VP') {
		array_shift($classParts);
		$file = dirname(__FILE__) . '/modules/php/' . implode(DIRECTORY_SEPARATOR, $classParts) . '.php';
		if (file_exists($file)) {
			require_once $file;
		} else {
			var_dump('Cannot find file : ' . $file);
		}
	}
};
spl_autoload_register($swdNamespaceAutoload, true, true);

require_once APP_GAMEMODULE_PATH . 'module/table/table.game.php';

use VP\Core\Globals;
use VP\Core\Preferences;
use VP\Helpers\UserException;
use VP\Managers\Cards;
use VP\Managers\Players;
use VP\Managers\PlayerTokens;

class villagepillage extends Table {
	use VP\DebugTrait;
	use VP\States\FarmersTrait;
	use VP\States\WallsTrait;
	use VP\States\RaidersTrait;
	use VP\States\MerchantsTrait;
	use VP\States\RefreshTrait;

	public static $instance = null;
	function __construct() {
		parent::__construct();
		self::$instance = $this;
		self::initGameStateLabels([
			'logging' => 10,
		]);
	}

	public static function get() {
		return self::$instance;
	}

	protected function getGameName() {
		return 'villagepillage';
	}

	/*
		   * setupNewGame:
	*/
	protected function setupNewGame($players, $options = []) {
		Globals::setupNewGame($players, $options);
		Preferences::setupNewGame($players, $options);
		Players::setupNewGame($players, $options);
		PlayerTokens::setupNewGame($players, $options);
		Cards::setupNewGame($players, $options);

		$this->gamestate->setAllPlayersMultiactive();
	}

	/*
		   * getAllDatas:
	*/
	public function getAllDatas() {
		$pId = self::getCurrentPId();
		return [
			'prefs' => Preferences::getUiData($pId),
			'players' => Players::getUiData($pId),
			'market' => Cards::getInLocation(['market']),
		];
	}

	function argPlayerTurn() {
		return [
			'market' => Cards::getInLocation(['market']),
			'players' => Players::getUiData(null),
		];
	}

	/*
		   * getGameProgression:
	*/
	function getGameProgression() {
		return 50; // TODO
	}

	function actChangePreference($pref, $value) {
		Preferences::set($this->getCurrentPId(), $pref, $value);
	}

	function actPlayCard($side, $cardId) {
		Cards::play($this->getCurrentPId(), $side, $cardId);
	}

	function actBuyCard($cardId) {
		Cards::buy($this->getCurrentPId(), $cardId);
	}

	function actEndTurn() {
		$pId = self::getCurrentPId();
		if (Cards::countInPlayOfPlayer($pId) == 2) {
			$this->gamestate->setPlayerNonMultiactive($this->getCurrentPlayerId(), 'done');
		} else {
			throw new UserException("Can only end turn after playing 2 cards");
		}
	}

	/////////////////////////////////////////////////////////////
	// Exposing protected methods, please use at your own risk //
	/////////////////////////////////////////////////////////////

	// Exposing protected method getCurrentPlayerId
	public static function getCurrentPId() {
		return self::getCurrentPlayerId();
	}

	// Exposing protected method translation
	public static function translate($text) {
		return self::_($text);
	}

	////////////////////////////////////
	////////////   Zombie   ////////////
	////////////////////////////////////
	/*
	   * zombieTurn:
	   *   This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
	   *   You can do whatever you want in order to make sure the turn of this player ends appropriately
*/
	public function zombieTurn($state, $activePlayer) {
		$statename = $state['name'];

		if ($state['type'] === 'activeplayer') {
			switch ($statename) {
			default:
				$this->gamestate->nextState('zombiePass');
				break;
			}

			return;
		}

		if ($state['type'] === 'multipleactiveplayer') {
			// Make sure player is in a non blocking status for role turn
			$this->gamestate->setPlayerNonMultiactive($active_player, '');

			return;
		}

		throw new feException('Zombie mode not supported at this game state: ' . $statename);
	}

	/////////////////////////////////////
	//////////   DB upgrade   ///////////
	/////////////////////////////////////
	// You don't have to care about this until your game has been published on BGA.
	// Once your game is on BGA, this method is called everytime the system detects a game running with your old Database scheme.
	// In this case, if you change your Database scheme, you just have to apply the needed changes in order to
	//   update the game database and allow the game to continue to run with your new version.
	/////////////////////////////////////
	/*
	   * upgradeTableDb
	   *  - int $from_version : current version of this game database, in numerical form.
	   *      For example, if the game was running with a release of your game named "140430-1345", $from_version is equal to 1404301345
*/
	public function upgradeTableDb($from_version) {
	}
}
