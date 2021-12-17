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
		'side' => 'side',
	];

	public function __construct($row) {
		foreach ($this->attributes as $attribute => $field) {
			$this->$attribute = $row[$field] ?? null;
		}
	}

	public function __call($method, $args) {
		if (preg_match('/^([gs]et|inc|is)([A-Z])(.*)$/', $method, $match)) {
			// Sanity check : does the name correspond to a declared variable ?
			$name = strtolower($match[2]) . $match[3];
			if (!\array_key_exists($name, $this->attributes)) {
				throw new \InvalidArgumentException("Attribute {$name} doesn't exist");
			}

			if ($match[1] == 'get') {
				// Basic getters
				return $this->$name;
			} elseif ($match[1] == 'is') {
				// Boolean getter
				return (bool) ($this->$name == 1);
			} elseif ($match[1] == 'set') {
				// Setters in DB and update cache
				$value = $args[0];
				$this->$name = $value;

				$updateValue = $value;
				if ($value != null) {
					$updateValue = \addslashes($value);
				}
				return $value;
			} elseif ($match[1] == 'inc') {
				$getter = 'get' . $match[2] . $match[3];
				$setter = 'set' . $match[2] . $match[3];
				return $this->$setter($this->$getter() + (empty($args) ? 1 : $args[0]));
			}
		} else {
			throw new \feException('Undefined method ' . $method);
			return null;
		}
	}

	public function jsonSerialize() {
		$data = [];
		foreach ($this->attributes as $attribute => $field) {
			$data[$attribute] = $this->$attribute;
		}

		return $data;
	}

	public function gain(&$player, $opposing_card, &$opposing_player) {}

	public function steal(&$player, $opposing_card, &$opposing_player) {}

	public function bank(&$player, $opposing_card, &$opposing_player) {}

	public function buy(&$player, $opposing_card, &$opposing_player) {}
}
