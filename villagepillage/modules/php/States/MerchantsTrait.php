<?php
namespace VP\States;

use VP\Core\Game;
use VP\Core\Globals;
use VP\Core\Notifications;
use VP\Managers\Cards;
use VP\Managers\Players;
use VP\Notifications\Poor;

trait MerchantsTrait {
	function stMerchants() {
		Notifications::message("Running Merchants");
		$players = Players::getAll();
		$merchants = [];
		$cards = Cards::getInLocation(['play', '%']);
		foreach ($cards as $card_id => $card) {
			if ($card->color == CARD_YELLOW) {
				$merchants[] = $card_id;
			}
		}
		Globals::setMerchants($merchants);
		if (count($merchants) == 0) {
			Game::get()->gamestate->nextState("none");
			return;
		}
		Game::get()->gamestate->nextState("done");
	}

	function stRunMerchant() {
		$merchants = Globals::getMerchants();
		$merchant_id = array_shift($merchants);
		Globals::setMerchants($merchants);
		if ($merchant_id === null) {
			Game::get()->gamestate->nextState("done");
			return;
		}
		$merchant = Cards::get($merchant_id);
		$owner = Players::get($merchant->pId);
		$opposing_player = Players::getNextId($merchant->pId);
		$opposing_card = Cards::getPlayerLeft($opposing_player);
		if ($merchant->side == 'left') {
			$opposing_player = Players::getPrevId($merchant->pId);
			$opposing_card = Cards::getPlayerRight($opposing_player);
		}
		$players = Players::getAll();
		$merchant->gain($owner, $opposing_card, $opposing_player);
		foreach ($players as &$player) {
			$player->updateIncome();
		}
		$merchant->steal($owner, $opposing_card, $opposing_player);
		foreach ($players as &$player) {
			$player->payThieves();
		}
		foreach ($players as &$player) {
			$player->updateIncome();
		}
		$merchant->bank($owner, $opposing_card, $opposing_player);
		foreach ($players as &$player) {
			$player->updateBank();
		}
		$buy = $merchant->buy($owner, $opposing_card, $opposing_player);
		if ($buy === true) {
			if ($merchant->buyPrice > $owner->getTurnips()) {
				Poor::poor($owner, $merchant->buyPrice);
				Game::get()->gamestate->nextState("next");
				return;
			}
			Globals::setBuyer($merchant->id);
			Globals::setBuyPrice($merchant->buyPrice);
			Game::get()->gamestate->changeActivePlayer($merchant->pId);
			Game::get()->giveExtraTime($merchant->pId);
			Game::get()->gamestate->nextState("buy");
			return;
		}
		Game::get()->gamestate->nextState("next");
	}

	function argBuy() {
		$merchant_id = Globals::getBuyer();
		$pId = Game::get()->getCurrentPId();
		return [
			'merchant' => Cards::get($merchant_id),
			'market' => Cards::getInLocation(['market']),
			'players' => Players::getUiData($pId),
		];
	}
}
