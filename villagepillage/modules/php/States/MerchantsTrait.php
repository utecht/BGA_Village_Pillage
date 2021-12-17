<?php
namespace VP\States;

use VP\Core\Game;
use VP\Core\Globals;
use VP\Core\Notifications;
use VP\Managers\Cards;
use VP\Managers\Players;

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
		// TODO: set this properly
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
		Notifications::message("Running merchant " . $merchant_id);
		$merchant = Cards::get($merchant_id);
		$opposing_player = Players::getNextId($merchant->pId);
		$opposing_card = Cards::getPlayerLeft($merchant->pId);
		if ($merchant->side == 'left') {
			$opposing_player = Players::getPrevId($merchant->pId);
			$opposing_card = Cards::getPlayerRight($merchant->pId);
		}
		$players = Players::getAll();
		$merchant->gain($player, $opposing_card, $opposing_player);
		foreach ($players as $player) {
			$player->updateIncome();
		}
		$merchant->steal($player, $opposing_card, $opposing_player);
		foreach ($players as $player) {
			$player->payThieves();
		}
		foreach ($players as $player) {
			$player->updateIncome();
		}
		$merchant->bank($player, $opposing_card, $opposing_player);
		foreach ($players as $player) {
			$player->updateBank();
		}
		$buy = $merchant->buy($player, $opposing_card, $opposing_player);
		if ($buy === true) {
			Globals::setBuyer($merchant->id);
			Globals::setBuyPrice($merchant->buyPrice);
			Game::get()->gamestate->changeActivePlayer($merchant->pId);
			Game::get()->gamestate->nextState("buy");
			return;
		} else {
			$player->incScore();
		}
		Game::get()->gamestate->nextState("next");
	}

	function argBuy() {
		$merchant_id = Globals::getBuyer();
		return [
			'merchant' => Cards::get($merchant_id),
			'market' => Cards::getInLocation(['market']),
		];
	}
}
