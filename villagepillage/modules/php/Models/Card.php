<?php
namespace VP\Models;

/*
 * Player: all utility functions concerning a player
 */
abstract class Card {
	protected $attributes = [
		'id' => 'id',
		'location' => 'location',
		'name' => 'name',
		'color' => 'color',
		'pId' => 'pId',
	];

	public function __construct($card) {
		foreach ($this->attributes as $attribute => $field) {
			$this->$attribute = $card[$field] ?? null;
		}
	}

	public function farm($player, $opposing_card, $opposing_player) {

	}

	public function wall($player, $opposing_card, $opposing_player) {

	}

	public function raid($player, $opposing_card, $opposing_player) {

	}

	public function merchant($player, $opposing_card, $opposing_player) {

	}
}
