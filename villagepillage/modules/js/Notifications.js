define(['dojo', 'dojo/_base/declare'], (dojo, declare) => {
  return declare('villagepillage.notifications', null, {
    notif_playCard(args){
      const player_id = args.args.player_id;
      const side = args.args.card_side;
      const target = `player-${side}-${player_id}`;
      if(player_id != this.player_id){
        dojo.destroy(`placeholder-${side}-${player_id}`);
        this.place('tplPlaceHolder', {side: side, player_id: player_id}, target);
      }
    },

    notif_playMyCard(args){
      const card_id = `card_${args.args.card.id}`;
      const player_id = args.args.player_id;
      const side = args.args.card_side;
      const target = `player-${side}-${player_id}`;
      for(const old_hand of dojo.query(`#${target} .card-wrapper`)){
        this.slide(old_hand.id, `player-hand-${player_id}`);
      }
      this.slide(card_id, target);
    },

    notif_buyCard(args){
      const card_id = `card_${args.args.card.id}`;
      const player_id = args.args.player_id;
      const target = `player-hand-${player_id}`;
      const amount = parseInt(args.args.amount);
      if(player_id == this.player_id){
        this.slide(card_id, target);
      } else {
        this.fadeOutAndDestroy(card_id);
      }
      this.players[player_id].supply -= amount;
      if(this.players[player_id].supply < 0){
        this.players[player_id].bank -= this.players[player_id].supply * -1;
        this.players[player_id].supply = 0;
      }
      this.wait(800).then(resolve => {
        this.refreshBank(this.players[player_id]);
      });
      this.updateCardTooltips();
    },

    notif_gainCard(args){
      const player_id = args.args.player_id;
      if(player_id == this.player_id){
        return;
      }
      const target = `player-hand-${player_id}`;
      const card_id = `placeholder-deck-${player_id}`;
      this.place('tplPlaceHolder', {side: 'deck', player_id: player_id}, 'deck');
      this.slide(card_id, `player-bank-area-${player_id}`, {destroy: true});
    },

    notif_poor(args){

    },

    notif_gainMyCard(args){
      const card = args.args.card;
      const card_id = `card_${card.id}`;
      const player_id = args.args.player_id;
      const target = `player-hand-${player_id}`;
      this.place('tplPlaceHolder', {side: card.id, player_id: player_id}, 'deck');
      this.flipAndReplace(`placeholder-${card.id}-${player_id}`, this.tplCard(card), 500);
      let _this = this;
      this.wait(500).then(resolve => {
        this.slide(card_id, target, {duration: 500});
        dojo.query(`#${card_id}`).connect('onclick', _this, 'onCardClick');
      });
      this.updateCardTooltips();
    },

    notif_flipCard(args){
      const card = args.args.card;
      const card_id = `card_${card.id}`;
      this.place('tplPlaceHolder', {side: 'deck', player_id: 'newCard'}, 'deck');
      this.flipAndReplace(`placeholder-deck-newCard`, this.tplCard(card), 500);
      let _this = this;
      this.wait(500).then(resolve => {
        this.slide(card_id, 'market', {duration: 500});
        dojo.query(`#${card_id}`).connect('onclick', _this, 'onCardClick');
      });
      this.updateCardTooltips();
    },

    notif_reveal(args){
      this.players = args.args.players;
      for(const player_id in this.players){
        this.players[player_id].supply = parseInt(this.players[player_id].supply);
        this.players[player_id].bank = parseInt(this.players[player_id].bank);
        this.players[player_id].relic = parseInt(this.players[player_id].relic);
        this.players[player_id].id = player_id;
      }
      for(const card_id in args.args.cards){
        const card = args.args.cards[card_id];
        if(card.pId != this.player_id){
          const placeholder = `placeholder-${card.side}-${card.pId}`;
          this.flipAndReplace(placeholder, this.tplOtherCard(card));
        }
      }
      this.updateCardTooltips();
    },

    notif_refresh(args){
      const player_id = this.player_id;
      const player_hand = `player-hand-${player_id}`;
      const player_left = dojo.query(`#player-left-${player_id} .card-wrapper`)[0].id;
      const player_right = dojo.query(`#player-right-${player_id} .card-wrapper`)[0].id;
      this.slide(player_left, player_hand);
      this.slide(player_right, player_hand);
      for(const card of dojo.query(`#player-exhausted-${player_id} .card-wrapper`)){
        this.slide(card.id, player_hand);
      }
      dojo.query('.other-exhausted .card-wrapper').forEach(dojo.destroy);
      for(const card_id in args.args.exhausted){
        const card = args.args.exhausted[card_id];
        this.slide(`card_${card_id}`, `player-exhausted-${card.pId}`);
      }
      dojo.query('.player-left .card-wrapper').forEach(dojo.destroy);
      dojo.query('.player-right .card-wrapper').forEach(dojo.destroy);
      this.updateCardTooltips();
    },

    notif_gain(args){
      console.log(this.players);
      const player = args.args.player;
      const card = args.args.card;
      const amount = parseInt(args.args.amount);
      this.place('tplTurnipSmall', args.args, `player-${card.side}-${player.id}-slide`);
      this.slide(`t_${card.id}_${player.id}`, `turnip-supply-${player.id}`, {destroy: true});
      this.players[player.id].supply += amount;
      this.wait(800).then(resolve => {
        this.refreshBank(this.players[player.id]);
      });
    },

    notif_steal(args){
      const player = args.args.player;
      const target = args.args.target;
      const amount = parseInt(args.args.amount);
      const card = args.args.card;
      this.place('tplTurnipSmall', args.args, `turnip-supply-${target.id}-target`);
      this.slide(`t_${card.id}_${player.id}`, `turnip-supply-${player.id}`, {destroy: true});
      this.players[player.id].supply += amount;
      this.players[target.id].supply -= amount;
      this.refreshBank(this.players[target.id]);
      this.wait(800).then(resolve => {
        this.refreshBank(this.players[player.id]);
      });
    },

    notif_stealBank(args){
      const player = args.args.player;
      const target = args.args.target;
      const amount = parseInt(args.args.amount);
      const card = args.args.card;
      const bank_num = this.players[target.id].bank - amount;
      this.place('tplTurnipSmall', args.args, `bank-turnip-${bank_num}-${target.id}`);
      this.slide(`t_${card.id}_${player.id}`, `turnip-supply-${player.id}`, {destroy: true});
      this.players[player.id].supply += amount;
      this.players[target.id].bank -= amount;
      this.refreshBank(this.players[target.id]);
      this.wait(800).then(resolve => {
        this.refreshBank(this.players[player.id]);
      });
    },

    notif_bank(args){
      const player = args.args.player;
      const amount = parseInt(args.args.amount);
      const card = args.args.card;
      this.players[player.id].supply -= amount;
      this.players[player.id].bank += amount;
      this.place('tplTurnipSmall', args.args, `turnip-supply-${player.id}-target`);
      this.slide(`t_${card.id}_${player.id}`, `bank-turnip-${this.players[player.id].bank}-${player.id}`, {destroy: true, pos: {x: '0px', y: '0px'}});
      this.wait(800).then(resolve => {
        this.refreshBank(this.players[player.id]);
      });
    },

    notif_buyRelic(args){
      const player = args.args.player;
      const card = args.args.card;
      const amount = parseInt(args.args.amount);
      let type = 'scepter';
      if(player.relic == 2){
        type = 'crown';
      }
      if(player.relic == 3){
        type = 'throne';
      }
      args.args['type'] = type;
      this.players[player.id].supply -= amount;
      if(this.players[player.id].supply < 0){
        this.players[player.id].bank -= this.players[player.id].supply * -1;
        this.players[player.id].supply = 0;
      }
      this.place('tplTurnipSmall', args.args, `turnip-supply-${player.id}-target`);
      this.slide(`t_${card.id}_${player.id}`, `player-${card.side}-${player.id}-slide`, {destroy: true});
      this.place('tplVictorySmall', args.args, `player-${card.side}-${player.id}-slide`)
      this.slide(`t_${type}_${player.id}`, `${type}-target-${player.id}`, {destroy: true, pos: {x: '0px', y: '0px'}});
      this.refreshBank(this.players[player.id]);
      this.players[player.id].relic += 1;
      this.wait(800).then(resolve => {
        this.refreshBank(this.players[player.id]);
        this.scoreCtrl[player.id].incValue(1);
      });
    },

  });
});
