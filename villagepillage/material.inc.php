<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * villagepillage implementation : © Joseph Utecht <joseph@utecht.co>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * material.inc.php
 *
 * villagepillage game material description
 *
 * Here, you can describe the material of your game with PHP variables.
 *
 * This file is loaded in your game logic class constructor, ie these variables
 * are available everywhere in your game logic code.
 *
 */

require_once 'modules/php/constants.inc.php';

$this->base_cards = array(
	['name' => 'florist', 'color' => CARD_GREEN],
	['name' => 'mason', 'color' => CARD_GREEN],
	['name' => 'rat_catcher', 'color' => CARD_GREEN],
	['name' => 'innkeeper', 'color' => CARD_GREEN],
	['name' => 'pickler', 'color' => CARD_GREEN],
	['name' => 'miner', 'color' => CARD_GREEN],
	['name' => 'labyrinth', 'color' => CARD_BLUE],
	['name' => 'cathedral', 'color' => CARD_BLUE],
	['name' => 'toll_bridge', 'color' => CARD_BLUE],
	['name' => 'moat', 'color' => CARD_BLUE],
	['name' => 'treasury', 'color' => CARD_BLUE],
	['name' => 'dungeon', 'color' => CARD_BLUE],
	['name' => 'berserker', 'color' => CARD_RED],
	['name' => 'outlaw', 'color' => CARD_RED],
	['name' => 'veteran', 'color' => CARD_RED],
	['name' => 'burglar', 'color' => CARD_RED],
	['name' => 'cutpurse', 'color' => CARD_RED],
	['name' => 'trapper', 'color' => CARD_RED],
	['name' => 'bard', 'color' => CARD_YELLOW],
	['name' => 'doctor', 'color' => CARD_YELLOW],
);