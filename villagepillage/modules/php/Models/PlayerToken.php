<?php
namespace VP\Models;

/*
 * Player: all utility functions concerning a player
 */
class PlayerToken extends \VP\Helpers\DB_Model {
	protected $table = 'player_tokens';
	protected $primary = 'player_id';
	protected $attributes = [
		'player_id' => 'player_id',
		'supply_turnips' => 'supply_turnips',
		'bank_turnips' => 'bank_turnips',
		'relic_state' => 'relic_state',
	];

	public function getId() {
		return (int) parent::getId();
	}

}
