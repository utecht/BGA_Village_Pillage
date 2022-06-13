define(['dojo', 'dojo/_base/declare'], (dojo, declare) => {
  return declare('villagepillage.utility', null, {

    updateCardTooltips() {
      dojo.query('.tooltip-target').forEach((card) => {
        this.addCustomTooltip(`card_${card.dataset.id}`, this.tplCardTooltip(card.dataset));
      });
    },

    highlightPlayCards() {
      dojo.query('.vp-possible-move').removeClass('vp-possible-move');
      dojo.query('.player-hand .card-click-target').addClass('vp-possible-move');
      dojo.query('.my-container .player-left .card-click-target').addClass('vp-possible-move');
      dojo.query('.my-container .player-right .card-click-target').addClass('vp-possible-move');
    },

    highlightZones() {
      dojo.query('.vp-possible-move').removeClass('vp-possible-move');
      dojo.query('.my-container .player-left').addClass('vp-possible-move');
      dojo.query('.my-container .player-right').addClass('vp-possible-move');
    },

    highlightBuyCards() {
      dojo.query('.vp-possible-move').removeClass('vp-possible-move');
      dojo.query('.market .card-click-target').addClass('vp-possible-move');
    },

  });
});
