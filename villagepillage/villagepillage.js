/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * villagepillage implementation : © Joseph Utecht <joseph@utecht.co>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * villagepillage.js
 *
 * villagepillage user interface script
 *
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

var isDebug = window.location.host == 'studio.boardgamearena.com' || window.location.hash.indexOf('debug') > -1;
var debug = isDebug ? console.info.bind(window.console) : function () {};

define([
  'dojo',
  'dojo/_base/declare',
  g_gamethemeurl + 'modules/js/vendor/nouislider.min.js',
  'ebg/core/gamegui',
  'ebg/counter',
  g_gamethemeurl + 'modules/js/Core/game.js',
  g_gamethemeurl + 'modules/js/Core/modal.js',
  g_gamethemeurl + 'modules/js/Templates.js',
  g_gamethemeurl + 'modules/js/Players.js',
  g_gamethemeurl + 'modules/js/States.js',
  g_gamethemeurl + 'modules/js/Actions.js',
  g_gamethemeurl + 'modules/js/Notifications.js',
  g_gamethemeurl + 'modules/js/Utility.js',
], function (dojo, declare, noUiSlider) {
  return declare('bgagame.villagepillage', [customgame.game,
                                            villagepillage.templates,
                                            villagepillage.players,
                                            villagepillage.actions,
                                            villagepillage.notifications,
                                            villagepillage.utility,
                                            villagepillage.states], {
    constructor() {
      this._activeStates = [];
      this._notifications = [
        ['playCard', 500],
        ['playMyCard', 500],
        ['refresh', 1500],
        ['gain', 1500],
        ['steal', 1500],
        ['stealBank', 1500],
        ['bank', 1500],
        ['buyCard', 1500],
        ['gainCard', 1500],
        ['gainMyCard', 1500],
        ['flipCard', 1500],
        ['poor', 500],
        ['buyRelic', 1500],
        ['reveal', 1500],
      ];

      // Fix mobile viewport (remove CSS zoom)
      this.default_viewport = 'width=700';
    },

    /**
     * Setup:
     *	This method set up the game user interface according to current game situation specified in parameters
     *	The method is called each time the game interface is displayed to a player, ie: when the game starts and when a player refreshes the game page (F5)
     *
     * Params :
     *	- mixed gamedatas : contains all datas retrieved by the getAllDatas PHP method.
     */
    setup(gamedatas) {
      debug('SETUP', gamedatas);
      this.setupMarket(gamedatas.market);
      this.setupPlayers();
      this.inherited(arguments);
    },
  });
});
