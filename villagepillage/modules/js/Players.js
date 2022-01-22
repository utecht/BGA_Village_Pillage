define(['dojo', 'dojo/_base/declare'], (dojo, declare) => {
  return declare('villagepillage.players', null, {
    // Utils to iterate over players array/object
    forEachPlayer(callback) {
      Object.values(this.gamedatas.players).forEach(callback);
    },

    getPlayerColor(pId) {
      return this.gamedatas.players[pId].color;
    },

    getPlayer(pId){
      return this.gamedatas.players[pId];
    },

    getThisPlayer(){
      return this.gamedatas.players[this.player_id];
    },

    setupPlayer(player, target_area){
      if(player.id == this.player_id){
        this.place('tplMyPlayerArea', player, target_area);
      } else {
        this.place('tplPlayerArea', player, target_area);
      }
      this.place('tplBank', player, `player-bank-area-${player.id}`);
      for(let t = 1; t <= player.bank; t++){
        this.place('tplTurnip', `${player.id}-bank-turnip_${t}`, `bank-turnip-${t}-${player.id}`);
      }
      if(player.relic >= 1){
        this.place('tplScepter', player, `scepter-target-${player.id}`);
      }
      if(player.relic >= 2){
        this.place('tplCrown', player, `crown-target-${player.id}`);
      }
      if(player.relic >= 3){
        this.place('tplThrone', player, `throne-target-${player.id}`);
      }
      player.cards.forEach((card) => {
        this.place('tplCard', card, `player-${card.location}-${player.id}`);
      });
      if(player.left){
        if(player.left == '1'){
          this.place('tplPlaceHolder', {
              side: 'left',
              player_id: player.id
            }, `player-left-${player.id}`);
        } else {
          this.place('tplCard', player.left, `player-left-${player.id}`);
        }
      }
      if(player.right){
        if(player.right == '1'){
          this.place('tplPlaceHolder', {
              side: 'right',
              player_id: player.id
            }, `player-right-${player.id}`);
        } else {
          this.place('tplCard', player.right, `player-right-${player.id}`);
        }
      }
    },

    setupPlayers() {
      const you = this.getThisPlayer();
      this.place('tplPlayerHand', you, 'main-container');
      this.place('tplAreas', null, 'main-container');
      const left_player = this.getPlayer(this.gamedatas.playerorder[this.gamedatas.playerorder.length - 1]);
      const right_player = this.getPlayer(this.gamedatas.playerorder[1]);
      const remaining_ids = this.gamedatas.playerorder.slice(2, -1);
      let remaining = [];
      remaining_ids.forEach((player_id) => {
        remaining.push(this.getPlayer(player_id));
      });

      console.log(left_player, you, right_player);
      console.log(remaining);
      this.setupPlayer(left_player, 'your-row');
      this.setupPlayer(you, 'your-row');
      this.setupPlayer(right_player, 'your-row');
      remaining.forEach((player) => {
        this.setupPlayer(player, 'remaining-opponents');
      });
      dojo.query('.card-wrapper').connect('onclick', this, 'onCardClick');
      dojo.query(`#player-left-${this.player_id}`).connect('onclick', this, 'onZoneClick');
      dojo.query(`#player-right-${this.player_id}`).connect('onclick', this, 'onZoneClick');
    },

    setupMarket(market){
      this.place('tplMarket', null, 'main-container');
      for(const card_id in market){
        const card = market[card_id];
        this.place('tplCard', card, `market`);
      }
      dojo.query('#market .card-wrapper').connect('onclick', this, 'onCardClick');
    },

    refreshMarket(market){
      dojo.query('#market .card-wrapper').forEach(dojo.destroy);
      for(const card_id in market){
        const card = market[card_id];
        this.place('tplCard', card, `market`);
      }
      dojo.query('#market .card-wrapper').connect('onclick', this, 'onCardClick');
    },

    refreshBank(player){
      dojo.destroy(`player-bank-${player.id}`);
      this.place('tplBank', player, `player-bank-area-${player.id}`);
      for(let t = 1; t <= player.bank; t++){
        this.place('tplTurnip', `${player.id}-bank-turnip_${t}`, `bank-turnip-${t}-${player.id}`);
      }
      if(player.relic >= 1){
        this.place('tplScepter', player, `scepter-target-${player.id}`);
      }
      if(player.relic >= 2){
        this.place('tplCrown', player, `crown-target-${player.id}`);
      }
      if(player.relic >= 3){
        this.place('tplThrone', player, `throne-target-${player.id}`);
      }
    },

  });
});
