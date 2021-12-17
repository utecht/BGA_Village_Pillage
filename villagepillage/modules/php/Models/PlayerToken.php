<?php
namespace VP\Models;

/*
 * Player: all utility functions concerning a player
 */
class PlayerToken extends \VP\Helpers\DB_Model {
	protected $table = 'player_tokens';
	protected $primary = 'player_id';
	protected $attributes = [
		'pId' => 'player_id',
		'supply' => 'supply',
		'bank' => 'bank',
		'relic' => 'relic',
	];

	public function getId() {
		return (int) parent::getId();
	}

}
