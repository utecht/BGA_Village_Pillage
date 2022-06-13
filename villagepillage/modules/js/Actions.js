define(['dojo', 'dojo/_base/declare'], (dojo, declare) => {
  return declare('villagepillage.actions', null, {
    onCardClick(evt){
      dojo.stopEvent(evt);
      // TODO: stop highlighting cards that are not valid plays
      const cardId = evt.currentTarget.id.split('_')[1];
      if(this.buying){
        this.takeAction('actBuyCard', {card_id: cardId});
        return;
      }
      if(this.selectedCardId === cardId){
        this.highlightPlayCards();
        this.selectedCardId = null;
      } else {
        this.highlightZones();
        this.selectedCardId = cardId;
      }
      dojo.toggleClass(evt.currentTarget.id, 'selected-card');
    },

    onZoneClick(evt){
      dojo.stopEvent(evt);
      const side = evt.currentTarget.id.split('-')[1];
      const cardId = this.selectedCardId;
      if(cardId == null){
        return;
      }
      this.takeAction('actPlayCard', {side: side, card_id: cardId});
      this.selectedCardId = null;
      this.highlightPlayCards();
      dojo.query('.selected-card').removeClass('selected-card');
    },

    onEndClick(evt){
      dojo.stopEvent(evt);
      dojo.query('vp-possible-move').removeClass('vp-possible-move');
      this.takeAction('actEndTurn');
    },
  });
});
