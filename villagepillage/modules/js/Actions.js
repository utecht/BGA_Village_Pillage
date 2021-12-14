define(['dojo', 'dojo/_base/declare'], (dojo, declare) => {
  return declare('villagepillage.actions', null, {
    onCardClick(evt){
      dojo.stopEvent(evt);
      const cardId = evt.currentTarget.dataset.id;
      if(this.selectedCardId === cardId){
        this.selectedCardId = null;
      } else {
        this.selectedCardId = cardId;
      }
      dojo.toggleClass(evt.currentTarget.id, 'selected-card');
    },

    onZoneClick(evt){
      dojo.stopEvent(evt);
      const side = evt.currentTarget.id.split('-')[1];
      const cardId = this.selectedCardId;
      this.takeAction('actPlayCard', {side: side, card_id: cardId});
      this.selectedCardId = null;
      dojo.query('.selected-card').removeClass('selected-card');
    }
  });
});
