<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * villagepillage implementation : ©  Joseph Utecht <joseph@utecht.co>
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 *
 * villagepillage.action.php
 *
 * villagepillage main action entry point
 *
 */

class action_villagepillage extends APP_GameAction {
	// Constructor: please do not modify
	public function __default() {
		if (self::isArg('notifwindow')) {
			$this->view = 'common_notifwindow';
			$this->viewArgs['table'] = self::getArg('table', AT_posint, true);
		} else {
			$this->view = 'villagepillage_villagepillage';
			self::trace('Complete reinitialization of board game');
		}
	}

	public function actChangePref() {
		self::setAjaxMode();
		$pref = self::getArg('pref', AT_posint, false);
		$value = self::getArg('value', AT_posint, false);
		$this->game->actChangePreference($pref, $value);
		self::ajaxResponse();
	}

	public function actPlayCard() {
		self::setAjaxMode();
		$side = self::getArg('side', AT_alphanum, false);
		$cardId = self::getArg('card_id', AT_posint, false);
		$this->game->actPlayCard($side, $cardId);
		self::ajaxResponse();
	}

	public function actBuyCard() {
		self::setAjaxMode();
		$cardId = self::getArg('card_id', AT_posint, false);
		$this->game->actBuyCard($cardId);
		self::ajaxResponse();
	}

	public function actEndTurn() {
		self::setAjaxMode();
		$this->game->actEndTurn();
		self::ajaxResponse();
	}
}
