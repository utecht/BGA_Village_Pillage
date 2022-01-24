<?php
namespace VP\Models;
use VP\Core\Game;
use VP\Core\Preferences;
use VP\Managers\Cards;
use VP\Managers\PlayerTokens;
use VP\Notifications\Steal;

/*
 * Player: all utility functions concerning a player
 */

class Player extends \VP\Helpers\DB_Model {
	protected $table = 'player';
	protected $primary = 'player_id';
	protected $attributes = [
		'id' => 'player_id',
		'no' => 'player_no',
		'name' => 'player_name',
		'color' => 'player_color',
		'eliminated' => 'player_eliminated',
		'score' => 'player_score',
		'zombie' => 'player_zombie',
	];
	protected $income = 0;
	protected $bankIncome = 0;
	protected $stolen = [];

	/*
		   * Getters
	*/
	public function getPref($prefId) {
		return Preferences::get($this->id, $prefId);
	}

	public function jsonSerialize($currentPlayerId = null) {
		$data = parent::jsonSerialize();
		$current = $this->id == $currentPlayerId;
		$data = array_merge($data, [
			'cards' => $current ? $this->getCards()->toArray() : $this->getExhausted()->toArray(),
		]);
		$data = array_merge($data, $this->getToken()->jsonSerialize());
		$left = Cards::getPlayerLeft($this->id);
		$left_count = $left ? 1 : 0;
		$revealed = Game::getStateName() != 'playerTurn';
		$revealed = $current ? true : $revealed;
		$data = array_merge($data, ['left' => $revealed ? $left : $left_count]);
		$right = Cards::getPlayerRight($this->id);
		$right_count = $right ? 1 : 0;
		$data = array_merge($data, ['right' => $revealed ? $right : $right_count]);

		return $data;
	}

	public function tokenSerialize() {
		$data = parent::jsonSerialize();
		$data = array_merge($data, $this->getToken()->jsonSerialize());
		return $data;
	}

	public function getId() {
		return (int) parent::getId();
	}

	public function getCards() {
		return Cards::getOfPlayer($this->id);
	}

	public function getExhausted() {
		return Cards::getOfPlayerExhausted($this->id);
	}

	public function getToken() {
		return PlayerTokens::get($this->id);
	}

	public function income($amount) {
		$this->income += $amount;
	}

	public function updateIncome() {
		$this->getToken()->incSupply($this->income);
		$this->income = 0;
	}

	public function bank($amount) {
		$this->bankIncome += $amount;
	}

	public function updateBank() {
		$token = $this->getToken();
		$available_supply = $token->supply;
		if ($available_supply < $this->bankIncome) {
			$this->bankIncome = $available_supply;
		}
		if ($this->bankIncome + $token->bank > 5) {
			$this->bankIncome = 5 - $token->bank;
		}
		$token->incSupply($this->bankIncome * -1);
		$token->incBank($this->bankIncome);
		$this->bankIncome = 0;
	}

	public function getTurnips() {
		$token = $this->getToken();
		return $token->supply + $token->bank;
	}

	public function spendTurnips($amount) {
		$token = $this->getToken();
		if ($amount > $token->supply) {
			$remaining = $amount - $token->supply;
			$token->incSupply($token->supply * -1);
			$token->incBank($remaining * -1);
		} else {
			$token->incSupply($amount * -1);
		}
	}

	protected function relicCost($token) {
		if ($token->relic == RELIC_NO) {
			return 8;
		} else if ($token->relic == RELIC_ONE) {
			return 9;
		} else if ($token->relic == RELIC_TWO) {
			return 10;
		} else if ($token->relic == RELIC_THREE) {
			return false;
		}
		return false;
	}

	public function buyRelic($discount) {
		$token = $this->getToken();
		$relic_cost = $this->relicCost($token);
		if ($relic_cost === false) {
			return false;
		}
		$relic_cost -= $discount;
		if ($this->getTurnips() >= $relic_cost) {
			$token->incRelic(1);
			$this->spendTurnips($relic_cost);
			return $relic_cost;
		}
		return false;
	}

	public function steal(&$stealing_player, $amount, $card) {
		$this->stolen[] = ['player' => $stealing_player, 'amount' => $amount, 'card' => $card];
	}

	public function payThieves() {
		if (count($this->stolen) === 0) {
			return;
		}
		$total_stolen = 0;
		$token = $this->getToken();
		$remainder = 0;
		foreach ($this->stolen as $thief) {
			$total_stolen += $thief['amount'];
		}
		$stolen_amount = 0;
		if ($total_stolen > $token->supply) {
			// on even steal, divide equally
			if ($token->supply % 2 == 0) {
				$stolen_amount = $token->supply / 2;
			} else {
				$stolen_amount = intval(floor($token->supply / 2));
				$remainder = 1;
			}
			$token->setSupply(0);
		} else {
			$token->incSupply($total_stolen * -1);
			foreach ($this->stolen as $thief) {
				$thief['player']->income($thief['amount']);
				Steal::steal($thief['player'], $thief['card'], $thief['amount'], $this);
			}
			$this->stolen = [];
			return;
		}
		if ($remainder === 1) {
			if (count($this->stolen) == 1) {
				$thief = &$this->stolen[0];
				$thief['player']->income($stolen_amount + $remainder);
				$thief['stole'] = $stolen_amount + $remainder;
				Steal::steal($thief['player'], $thief['card'], $stolen_amount + $remainder, $this);
			} else {
				$thief0 = &$this->stolen[0];
				$thief1 = &$this->stolen[1];
				$greaterThief = &$thief0;
				$lesserThief = &$thief1;
				if ($thief1['amount'] > $thief0['amount']) {
					$greaterThief = &$thief1;
					$lesserThief = &$thief0;
				} elseif ($thief0['amount'] == $thief1['amount']) {
					if (bga_rand(0, 1) == 1) {
						$greaterThief = &$thief1;
						$lesserThief = &$thief0;
					}
				}
				$greaterThief['player']->income($stolen_amount + $remainder);
				$greaterThief['stole'] = $stolen_amount + $remainder;
				Steal::steal($greaterThief['player'], $greaterThief['card'], $stolen_amount + $remainder, $this);
				$lesserThief['player']->income($stolen_amount);
				$lesserThief['stole'] = $stolen_amount;
				Steal::steal($lesserThief['player'], $lesserThief['card'], $stolen_amount, $this);

			}
		} else {
			foreach ($this->stolen as &$thief) {
				$thief['player']->income($stolen_amount);
				$thief['stole'] = $stolen_amount;
				Steal::steal($thief['player'], $thief['card'], $stolen_amount, $this);
			}
		}
		$bankThieves = [];
		foreach ($this->stolen as &$thief) {
			if ($thief['stole'] < $thief['amount']) {
				if ($thief['card']->bankSteal()) {
					$thief['bankSteal'] = $thief['amount'] - $thief['stole'];
					$bankThieves[] = $thief;
				}
			}
		}

		if (count($bankThieves) === 0) {
			return;
		}

		$total_stolen = 0;
		$remainder = 0;
		foreach ($bankThieves as $thief) {
			$total_stolen += $thief['bankSteal'];
		}
		$stolen_amount = 0;
		if ($total_stolen > $token->bank) {
			// on even steal, divide equally
			if ($token->bank % 2 == 0) {
				$stolen_amount = $token->bank / 2;
			} else {
				$stolen_amount = intval(floor($token->bank / 2));
				$remainder = 1;
			}
			$token->setBank(0);
		} else {
			$token->incBank($total_stolen * -1);
			foreach ($bankThieves as $thief) {
				$thief['player']->income($thief['bankSteal']);
				Steal::stealBank($thief['player'], $thief['card'], $thief['bankSteal'], $this);
			}
			$this->stolen = [];
			return;
		}

		if ($remainder === 1) {
			if (count($bankThieves) == 1) {
				$thief = &$bankThieves[0];
				$thief['player']->income($stolen_amount + $remainder);
				$thief['stole'] = $stolen_amount + $remainder;
				Steal::stealBank($thief['player'], $thief['card'], $stolen_amount + $remainder, $this);
			} else {
				$thief0 = &$bankThieves[0];
				$thief1 = &$bankThieves[1];
				$greaterThief = &$thief0;
				$lesserThief = &$thief1;
				if ($thief1['amount'] > $thief0['amount']) {
					$greaterThief = &$thief1;
					$lesserThief = &$thief0;
				} elseif ($thief0['amount'] == $thief1['amount']) {
					if (bga_rand(0, 1) == 1) {
						$greaterThief = &$thief1;
						$lesserThief = &$thief0;
					}
				}
				$greaterThief['player']->income($stolen_amount + $remainder);
				Steal::stealBank($greaterThief['player'], $greaterThief['card'], $stolen_amount + $remainder, $this);
				$lesserThief['player']->income($stolen_amount);
				Steal::stealBank($lesserThief['player'], $lesserThief['card'], $stolen_amount, $this);
			}
		} else {
			foreach ($this->stolen as $thief) {
				$thief['player']->income($stolen_amount);
				Steal::stealBank($thief['player'], $thief['card'], $stolen_amount, $this);
			}
		}

		$this->stolen = [];
	}
}
