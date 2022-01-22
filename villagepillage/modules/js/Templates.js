define(['dojo', 'dojo/_base/declare'], (dojo, declare) => {
	return declare('villagepillage.templates', null, {
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
		        <div class='player-left' id="player-left-${player.id}">
			        <span>Left Card</span>
		        	<div id="player-left-${player.id}-slide" class="slide-target"></div>
		        </div>
		        <div>
		          <div id="player-bank-area-${player.id}"></div>
		          <div class='player-exhausted' id="player-exhausted-${player.id}"></div>
		        </div>
		        <div class='player-right' id="player-right-${player.id}">
			        <span>Right Card</span>
		        	<div id="player-right-${player.id}-slide" class="slide-target"></div>
		        </div>
		      </div>
		    </div>
		  `;
		},

		tplMyPlayerArea(player) {
		  return `
		    <div class='player-container' style='border-color:#${player.color}'>
		      <div id="player-name-${player.id}" class='player-name' style='color:#${player.color}'>You</div>
		      <div class='player-area'>
		        <div class='player-left' id="player-left-${player.id}">
			        <span class="player-name">Play Left</span>
		        	<div id="player-left-${player.id}-slide" class="slide-target"></div>
		        </div>
		        <div>
		          <div id="player-bank-area-${player.id}"></div>
		          <div class='player-exhausted' id="player-exhausted-${player.id}"></div>
		        </div>
		        <div class='player-right' id="player-right-${player.id}">
			        <span class="player-name">Play Right</span>
		        	<div id="player-right-${player.id}-slide" class="slide-target"></div>
		        </div>
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

		tplTurnipSmall(args){
		  return `
		    <div id="t_${args.card.id}_${args.player.id}" class="turnip-wrapper">
			    <div class="token token-turnip">${args.amount}</div>
		    </div>
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
		},
	});
});