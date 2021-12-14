<?php
namespace VP\Managers;
use VP\Core\Game;

/*
 * Tokens manager : allows to easily access tokens ...
 *  a token is an instance of PlayerToken class
 */
class PlayerTokens extends \VP\Helpers\DB_Manager {
	protected static $table = 'player_tokens';
	protected static $primary = 'player_id';
	protected static function cast($row) {
		return new \VP\Models\PlayerToken($row);
	}

	public function setupNewGame($players, $options) {
		// Create tokens
		$gameInfos = Game::get()->getGameinfos();
		$query = self::DB()->multipleInsert([
			'player_id',
			'supply_turnips',
			'bank_turnips',
			'relic_state',
		]);

		$values = [];
		foreach ($players as $pId => $player) {
			$values[] = [$pId, 1, 1, RELIC_NO];
		}
		$query->values($values);
	}

	/*
		   * get : returns the Player object for the given player ID
	*/
	public function get($pId = null) {
		$pId = $pId ?: self::getActiveId();
		return self::DB()
			->where($pId)
			->getSingle();
	}

	public function getMany($pIds) {
		$players = self::DB()
			->whereIn($pIds)
			->get();
		return $players;
	}

}
