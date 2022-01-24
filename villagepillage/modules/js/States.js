define(['dojo', 'dojo/_base/declare'], (dojo, declare) => {
  return declare('villagepillage.states', null, {
    onEnteringStatePlayerTurn(args){
      debug(args);
      this.buying = false;
      this.refreshThings(args.players, args.market);
    },

    onUpdateActivityPlayerTurn(args, status){
      this.addPrimaryActionButton('end_turn', _('End Turn'), 'onEndClick');
    },

    onEnteringStateBuy(args){
      this.buying = true;
      this.refreshThings(args.players, args.market);
    },

    refreshThings(players, market){
      this.refreshMarket(market);
      for(let player in players){
        this.refreshBank(players[player]);
      }
    },
  });
});
