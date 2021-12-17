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
 * states.inc.php
 *
 * villagepillage game states description
 *
 */

$machinestates = [
	// The initial state. Please do not modify.
	ST_GAME_SETUP => [
		'name' => 'gameSetup',
		'description' => '',
		'type' => 'manager',
		'action' => 'stGameSetup',
		'transitions' => ['' => ST_CARD_PLAY],
	],

	ST_CARD_PLAY => [
		'name' => 'playerTurn',
		'description' => clienttranslate('All players must play cards'),
		'descriptionmyturn' => clienttranslate('All players must play cards'),
		'type' => 'multipleactiveplayer',
		'possibleactions' => ['actPlayCard'],
		'transitions' => ['done' => ST_FARMERS],
	],

	ST_FARMERS => [
		'name' => 'farmers',
		'description' => clienttranslate('Activating farmers'),
		'type' => 'manager',
		'action' => 'stFarmers',
		'transitions' => ['done' => ST_WALLS],
	],

	ST_WALLS => [
		'name' => 'walls',
		'description' => clienttranslate('Activating walls'),
		'type' => 'manager',
		'action' => 'stWalls',
		'transitions' => ['done' => ST_RAIDERS],
	],

	ST_RAIDERS => [
		'name' => 'raiders',
		'description' => clienttranslate('Activating raiders'),
		'type' => 'manager',
		'action' => 'stRaiders',
		'transitions' => ['done' => ST_MERCHANTS],
	],

	ST_MERCHANTS => [
		'name' => 'merchants',
		'description' => clienttranslate('Activating merchants'),
		'type' => 'manager',
		'action' => 'stMerchants',
		'transitions' => ['done' => ST_RUN_MERCHANT, 'multi' => ST_MERCHANT_PICK, 'none' => ST_REFRESH],
	],

	ST_RUN_MERCHANT => [
		'name' => 'run_merchant',
		'description' => clienttranslate('Running merchant'),
		'type' => 'manager',
		'action' => 'stRunMerchant',
		'transitions' => ['buy' => ST_BUY, 'next' => ST_RUN_MERCHANT, 'done' => ST_REFRESH],
	],

	ST_BUY => [
		'name' => 'merchant_buy',
		'description' => clienttranslate('Player must buy a card'),
		'descriptionmyturn' => clienttranslate('You must buy a card'),
		'type' => 'activeplayer',
		'args' => 'argBuy',
		'possibleactions' => ['actBuyCard'],
		'transitions' => ['done' => ST_RUN_MERCHANT],
	],

	ST_MERCHANT_PICK => [
		'name' => 'merchant_buy',
		'description' => clienttranslate('Players must pick first merchant to activate'),
		'descriptionmyturn' => clienttranslate('You must pick first merchant to activate'),
		'type' => 'multipleactiveplayer',
		'args' => 'argMulti',
		'possibleactions' => ['actPickFirst'],
		'transitions' => ['done' => ST_RUN_MERCHANT],
	],

	ST_REFRESH => [
		'name' => 'refresh',
		'description' => clienttranslate('Refresh'),
		'type' => 'manager',
		'action' => 'stRefresh',
		'transitions' => ['next' => ST_CARD_PLAY, 'end' => ST_END_GAME],
	],

	// Final state.
	// Please do not modify (and do not overload action/args methods).
	ST_END_GAME => [
		'name' => 'gameEnd',
		'description' => clienttranslate('End of game'),
		'type' => 'manager',
		'action' => 'stGameEnd',
		'args' => 'argGameEnd',
	],
];
