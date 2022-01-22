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
      if(player_id == this.player_id){
        this.slide(card_id, target);
      } else {
        this.fadeOutAndDestroy(card_id);
      }
    },

    notif_gainCard(args){
      // slide a card off the deck to player
    },

    notif_gainMyCard(args){
      const card_id = `card_${args.args.card.id}`;
      const player_id = args.args.player_id;
      const target = `player-hand-${player_id}`;
      this.place('tplCard', card, target);
      dojo.connect(card_id, 'onclick', this, 'onCardClick');
    },

    notif_reveal(args){
      for(const card_id in args.args.cards){
        const card = args.args.cards[card_id];
        if(card.pId != this.player_id){
          const placeholder = `placeholder-${card.side}-${card.pId}`;
          this.flipAndReplace(placeholder, this.tplOtherCard(card));
        }
      }
    },

    notif_refresh(args){
      const player_id = this.player_id;
      const player_hand = `player-hand-${player_id}`;
      const player_left = dojo.query(`#player-left-${player_id} .card-wrapper`)[0].id;
      const player_right = dojo.query(`#player-right-${player_id} .card-wrapper`)[0].id;
      this.slide(player_left, player_hand);
      this.slide(player_right, player_hand);
      // TODO: slide my exhausted cards back to hand and destroy opponents
      for(const card_id in args.args.exhausted){
        const card = args.args.exhausted[card_id];
        this.slide(`card_${card_id}`, `player-exhausted-${card.pId}`);
      }
      dojo.query('.player-left .card-wrapper').forEach(dojo.destroy);
      dojo.query('.player-right .card-wrapper').forEach(dojo.destroy);
    },

    notif_gain(args){
      const player = args.args.player;
      const card = args.args.card;
      const amount = args.args.amount;
      this.place('tplTurnipSmall', args.args, `player-${card.side}-${player.id}-slide`);
      this.slide(`t_${card.id}_${player.id}`, `turnip-supply-${player.id}`, {destroy: true});
      player.supply = parseInt(player.supply) + parseInt(amount);
      this.wait(800).then(resolve => {
        this.refreshBank(player);
      });
    },

    notif_steal(args){
      const player = args.args.player;
      const target = args.args.target;
      const amount = args.args.amount;
      const card = args.args.card;
      this.place('tplTurnipSmall', args.args, `turnip-supply-${target.id}`);
      this.slide(`t_${card.id}_${player.id}`, `turnip-supply-${player.id}`, {destroy: true});
      player.supply = parseInt(player.supply) + parseInt(amount);
      this.refreshBank(target);
      this.wait(800).then(resolve => {
        this.refreshBank(player);
      });
    },

    notif_bank(args){
      const player = args.args.player;
      const amount = args.args.amount;
      const card = args.args.card;
      player.bank = parseInt(player.bank) + parseInt(amount);
      player.supply = parseInt(player.supply) - parseInt(amount);
      this.place('tplTurnipSmall', args.args, `turnip-supply-${player.id}`);
      this.slide(`t_${card.id}_${player.id}`, `bank-turnip-${player.bank}-${player.id}`, {destroy: true, pos: {x: '0px', y: '0px'}});
      this.wait(800).then(resolve => {
        this.refreshBank(player);
      });
    },

    notif_buyRelic(args){
      const player = args.args.player;
      this.refreshBank(player);
      this.scoreCtrl[player.id].incValue(1);
    },

  });
});
