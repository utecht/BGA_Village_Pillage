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

    tplAreas(){
      return `
        <div id="your-row"></div>
        <div id="remaining-opponents"></div>
      `;
    },

    tplMarket(){
      return `
        <div class="player-container" style='border-color:gold'>
          <div class='player-name' style='color:gold'>Market</div>
          <div id="market" class="market"></div>
        </div>
      `;
    },

    tplPlayerArea(player) {
      return `
        <div class='player-container' style='border-color:#${player.color}'>
          <div class='player-name' style='color:#${player.color}'>${player.name}</div>
          <div class='player-area'>
            <div class='player-left' id="player-left-${player.id}"><span>Left Card</span></div>
            <div>
              <div id="player-bank-area-${player.id}"></div>
              <div class='player-exhausted' id="player-exhausted-${player.id}"></div>
            </div>
            <div class='player-right' id="player-right-${player.id}"><span>Right Card</span></div>
          </div>
        </div>
      `;
    },

    tplMyPlayerArea(player) {
      return `
        <div class='player-container' style='border-color:#${player.color}'>
          <div id="player-name-${player.id}" class='player-name' style='color:#${player.color}'>You</div>
          <div class='player-area'>
            <div class='player-left' id="player-left-${player.id}"><span class="player-name">Play Left</span></div>
            <div>
              <div id="player-bank-area-${player.id}"></div>
              <div class='player-exhausted' id="player-exhausted-${player.id}"></div>
            </div>
            <div class='player-right' id="player-right-${player.id}"><span class="player-name">Play Right</span></div>
          </div>
        </div>
      `;
    },

    tplBank(player){
      let supply = this.tplTurnipSupply(player);
      return `
        <div id="player-bank-${player.id}">
          <div class="bank-wrap">
            <div id="player-bank-${player.id}" class='player-bank bank bank_card'>
              <div id="scepter-target-${player.id}" class="scepter-target"></div>
              <div id="crown-target-${player.id}" class="crown-target"></div>
              <div id="throne-target-${player.id}" class="throne-target"></div>
              <div id="bank-turnip-1-${player.id}" class="bank-turnip-1"></div>
              <div id="bank-turnip-2-${player.id}" class="bank-turnip-2"></div>
              <div id="bank-turnip-3-${player.id}" class="bank-turnip-3"></div>
              <div id="bank-turnip-4-${player.id}" class="bank-turnip-4"></div>
              <div id="bank-turnip-5-${player.id}" class="bank-turnip-5"></div>
            </div>
          </div>
          <div class="player-supply-wrap"><div class="player-supply">${supply}</div></div>
        </div>
      `;
    },

    tplTurnip(id){
      return `
        <div id="${id}" class="token token-turnip"></div>
      `;
    },

    tplTurnipSupply(player){
      return `
        <div id="turnip-supply-${player.id}" class="token token-turnip"><h1>${player.supply}</h1></div>
      `;
    },

    tplScepter(player){
      return `
        <div id="scepter-${player.id}" class="token token-scepter"></div>
      `;
    },

    tplCrown(player){
      return `
        <div id="crown-${player.id}" class="token token-crown"></div>
      `;
    },

    tplThrone(player){
      return `
        <div id="throne-${player.id}" class="token token-throne"></div>
      `;
    },

    tplPlayerHand(player){
      return `
        <div class='player-container'
             style='border-color:#${player.color}'>
             <div class='player-name' style='color:#${player.color}'>Your Hand</div>
             <div id='player-hand-${player.id}' class='player-hand'></div>
        </div>
      `;

    },

    tplCard(card) {
      return `
        <div id="card_${card.id}" class='card-wrapper'><div class='card card_${card.name}' data-id='${card.id}' data-name='${card.name}'></div></div>
      `;
    },

    tplOtherCard(card){
      return `
        <div id="card_${card.id}" class='card-wrapper other-player-card'><div class='card card_${card.name}' data-id='${card.id}' data-name='${card.name}'></div></div>
      `;
    },

    tplPlaceHolder(args){
      return `
        <div id="placeholder-${args.side}-${args.player_id}" class='card-wrapper'><div class='card card_back'></div></div>
      `;
    }
  });
});
