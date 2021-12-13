define(['dojo', 'dojo/_base/declare'], (dojo, declare) => {
  return declare('villagepillage.states', null, {
    onEnteringStatePlayerTurn(args){
      debug(args);
    },
  });
});
