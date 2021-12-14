<?php
error_reporting(E_ALL ^ E_DEPRECATED);
define("APP_GAMEMODULE_PATH", "./"); // include path to mocks, this defined "Table" and other classes
require_once '../villagepillage.game.php'; // include real game class

class VillagePillageTest extends VillagePillage {

	function __construct() {
		parent::__construct();
		include '../material.inc.php';
		$this->resources = array();
	}
	// override methods here that access db and stuff

	function getGameStateValue($var) {
		if ($var == 'round') {
			return 3;
		}

		return 0;
	}
}

$t = new VillagePillageTest();

$t::setupNewGame([1, 2, 3]);
$t::getAllDatas();