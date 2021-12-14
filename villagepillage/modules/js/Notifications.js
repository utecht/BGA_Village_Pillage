define(['dojo', 'dojo/_base/declare'], (dojo, declare) => {
  return declare('villagepillage.notifications', null, {
    notif_playCard(args){
      const player_id = args.args.player_id;
      const side = args.args.card_location;
      const target = `player-${side}-${player_id}`;
      if(player_id != this.player_id){
        dojo.destroy(`placeholder-${side}-${player_id}`);
        this.place('tplPlaceHolder', {side: side, player_id: player_id}, target);
      }
    },

    notif_playMyCard(args){
      const card_id = `card_${args.args.card.id}`;
      const player_id = args.args.player_id;
      const side = args.args.card_location;
      const target = `player-${side}-${player_id}`;
      console.log(card_id, target);
      for(const old_hand of dojo.query(`#${target} .card`)){
        this.slide(old_hand.id, `player-hand-${player_id}`);
      }
      this.slide(card_id, target);
    },

  });
});
