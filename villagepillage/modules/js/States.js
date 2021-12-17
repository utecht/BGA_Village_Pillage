define(['dojo', 'dojo/_base/declare'], (dojo, declare) => {
  return declare('villagepillage.states', null, {
    onEnteringStatePlayerTurn(args){
      debug(args);
      this.buying = false;
      this.refreshMarket(args.market);
    },

    onEnteringStateBuy(args){
      this.buying = true;
      this.refreshMarket(args.market);
    },
  });
});
