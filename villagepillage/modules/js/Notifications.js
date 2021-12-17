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
      for(const old_hand of dojo.query(`#${target} .card`)){
        this.slide(old_hand.id, `player-hand-${player_id}`);
      }
      this.slide(card_id, target);
    },

    notif_reveal(args){
      dojo.query('.placeholder').forEach(dojo.destroy);
      for(const card_id in args.args.cards){
        const card = args.args.cards[card_id];
        if(card.pId != this.player_id){
          const target = `player-${card.side}-${card.pId}`;
          this.place('tplOtherCard', card, target);
        }
      }
    },

    notif_refresh(args){
      const player_id = this.player_id;
      const player_hand = `player-hand-${player_id}`;
      const player_left = `player-left-${player_id}`;
      const player_right = `player-right-${player_id}`;
      this.slide(player_left, player_hand);
      this.slide(player_right, player_hand);
      for(const card_id in args.args.exhausted){
        const card = args.args.cards[card_id];
        this.slide(`card_${card_id}`, `player-exhausted-${card.pId}`);
      }
      dojo.query('.player-left .other-player-card').forEach(dojo.destroy);
      dojo.query('.player-right .other-player-card').forEach(dojo.destroy);
    },

    notif_gain(args){
      const player = args.args.player;
      this.refreshBank(player);
    },

    notif_steal(args){
      const player = args.args.player;
      this.refreshBank(player);
    },

    notif_bank(args){
      const player = args.args.player;
      this.refreshBank(player);
    },

    notif_buyRelic(args){
      const player = args.args.player;
      this.refreshBank(player);
    },

  });
});
