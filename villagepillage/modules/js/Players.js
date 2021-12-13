define(['dojo', 'dojo/_base/declare'], (dojo, declare) => {
  return declare('villagepillage.players', null, {
    // Utils to iterate over players array/object
    forEachPlayer(callback) {
      Object.values(this.gamedatas.players).forEach(callback);
    },

    getPlayerColor(pId) {
      return this.gamedatas.players[pId].color;
    },

    setupPlayers() {
      this.forEachPlayer((player) => {
        if(player.cards.length > 0){
          this.place('tplPlayerHand', player, 'main-container');
          player.cards.forEach((card) => {
            this.place('tplCard', card, 'player-hand-' + player.id);
          });
        }

        this.place('tplPlayerArea', player, 'main-container');

        if(player.left){
          this.place('tplCard', player.left, `player-left-${player.id}`);
        }
        if(player.right){
          this.place('tplCard', player.right, `player-right-${player.id}`);
        }
      });
      dojo.query('.player-hand .card').connect('onclick', this, 'onCardClick');
    },

    tplPlayerArea(player) {
      return `
        <div class='player-container' style='border-color:#${player.color}'>
          <div class='player-name' style='color:#${player.color}'>${player.name}</div>
          <div class='player-area'>
            <div class='player-left' id="player-left-${player.id}"></div>
            <div class='player-bank card card_bank' id="player-bank-${player.id}"></div><span>Bank = ${player.bank_turnips}</span>
            <div class='player-supply' id="player-supply-${player.id}"></div><span>Supply = ${player.supply_turnips}</span>
            <div class='player-right' id="player-right-${player.id}"></div>
          </div>
        </div>
      `;
    },

    tplPlayerHand(player){
      return `
        <div class='player-hand'
             style='border-color:#${player.color}'
             id="player-hand-${player.id}">
        </div>
      `;

    },

    tplCard(card) {
      return `
        <div class='card card_${card.name}' data-id='${card.id}' data-name='${card.name}'></div>
      `;
    },
  });
});
