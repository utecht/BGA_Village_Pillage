define(['dojo', 'dojo/_base/declare'], (dojo, declare) => {
  return declare('villagepillage.actions', null, {
    onCardClick(evt){
      dojo.stopEvent(evt);
      const cardId = evt.currentTarget.dataset.id;
      this.takeAction('actPlayCard', {side: 'left', card_id: cardId});
    },
  });
});
