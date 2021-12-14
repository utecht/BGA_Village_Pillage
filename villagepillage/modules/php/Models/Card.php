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

	public function farm() {

	}

	public function wall() {

	}

	public function raid() {

	}

	public function merchant() {

	}
}
