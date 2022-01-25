define(['dojo', 'dojo/_base/declare'], (dojo, declare) => {
  return declare('villagepillage.states', null, {
    onEnteringStatePlayerTurn(args){
      this.buying = false;
      this.refreshThings(args.players, args.market);
    },

    onUpdateActivityPlayerTurn(args, status){
      this.addPrimaryActionButton('end_turn', _('End Turn'), 'onEndClick');
    },

    onEnteringStateBuy(args){
      this.buying = true;
      this.refreshThings(args.players, args.market);
      this.players = args.players;
      for(const player_id in this.players){
        this.players[player_id].supply = parseInt(this.players[player_id].supply);
        this.players[player_id].bank = parseInt(this.players[player_id].bank);
        this.players[player_id].relic = parseInt(this.players[player_id].relic);
      }
    },

    refreshThings(players, market){
      //this.refreshMarket(market);
      for(let player in players){
        this.refreshBank(players[player]);
      }
    },
  });
});
