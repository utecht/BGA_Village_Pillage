define(['dojo', 'dojo/_base/declare'], (dojo, declare) => {
  return declare('villagepillage.utility', null, {

    updateCardTooltips() {
      dojo.query('.tooltip-target').forEach((card) => {
        this.addCustomTooltip(`card_${card.dataset.id}`, this.tplCardTooltip(card.dataset));
      });
    },
  });
});
