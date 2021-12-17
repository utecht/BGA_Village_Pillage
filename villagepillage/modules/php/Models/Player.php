<?php
namespace VP\Models;
use VP\Core\Preferences;
use VP\Managers\Cards;
use VP\Managers\PlayerTokens;

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
		$data = array_merge($data, ['left' => $current ? $left : $left_count]);
		$right = Cards::getPlayerRight($this->id);
		$right_count = $right ? 1 : 0;
		$data = array_merge($data, ['right' => $current ? $right : $right_count]);

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

	public function steal(&$stealing_player, $amount) {
		$this->stolen[] = ['player' => $stealing_player, 'amount' => $amount];
	}

	public function payThieves() {
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
			}
		}
		if ($remainder === 1) {
			if ($this->stolen[0] > $this->stolen[1]) {
				$this->stolen[0]['player']->income($stolen_amount + $remainder);
				$this->stolen[1]['player']->income($stolen_amount);
			} elseif ($this->stolen[1] > $this->stolen[0]) {
				$this->stolen[1]['player']->income($stolen_amount + $remainder);
				$this->stolen[0]['player']->income($stolen_amount);
			} else {
				if (bga_rand(0, 1) == 1) {
					$this->stolen[1]['player']->income($stolen_amount + $remainder);
					$this->stolen[0]['player']->income($stolen_amount);
				} else {
					$this->stolen[0]['player']->income($stolen_amount + $remainder);
					$this->stolen[1]['player']->income($stolen_amount);
				}
			}
		} else {
			foreach ($this->stolen as $thief) {
				if ($thief['amount'] <= $stolen_amount) {
					$thief['player']->income($stolen_amount);
				} else {
					$thief['player']->income($thief['amount']);
				}
			}
		}
		$this->stolen = [];
	}
}
