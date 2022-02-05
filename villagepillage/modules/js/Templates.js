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
		      <div id="market" class="market">
			    <div class='card-wrapper'>
				    <div id="deck"></div>
			    	<div class='card-border'>
					    <div class='card card_back'></div>
				    </div>
			    </div>
			    <div id="market-target"></div>
		      </div>
		    </div>
		  `;
		},

		tplDeck(){
			return `
			    <div class='card-wrapper'>
				    <div id="deck"></div>
			    	<div class='card-border'>
					    <div class='card card_back'></div>
				    </div>
			    </div>
		    `;
		},

		tplPlayerArea(player) {
		  return `
		    <div class='player-container other-container' style='border-color:#${player.color}'>
		      <div class='player-name' style='color:#${player.color}'>${player.name}</div>
		      <div class='player-area'>
		        <div class='player-left' id="player-left-${player.id}">
			        <i class="fa fa-arrow-left"></i>
		        	<div id="player-left-${player.id}-slide" class="slide-target"></div>
		        </div>
		        <div>
		          <div id="player-bank-area-${player.id}"></div>
		          <div class='player-exhausted other-exhausted' id="player-exhausted-${player.id}"></div>
		        </div>
		        <div class='player-right' id="player-right-${player.id}">
			        <i class="fa fa-arrow-right"></i>
		        	<div id="player-right-${player.id}-slide" class="slide-target"></div>
		        </div>
		      </div>
		    </div>
		  `;
		},

		tplReversedPlayerArea(player) {
		  return `
		    <div class='player-container reversed-container' style='border-color:#${player.color}'>
		      <div class='player-name' style='color:#${player.color}'>${player.name}</div>
		      <div class='player-area'>
		        <div class='player-left' id="player-right-${player.id}">
			        <i class="fa fa-arrow-left"></i>
		        	<div id="player-right-${player.id}-slide" class="slide-target"></div>
		        </div>
		        <div>
		          <div id="player-bank-area-${player.id}"></div>
		          <div class='player-exhausted other-exhausted' id="player-exhausted-${player.id}"></div>
		        </div>
		        <div class='player-right' id="player-left-${player.id}">
			        <i class="fa fa-arrow-right"></i>
		        	<div id="player-left-${player.id}-slide" class="slide-target"></div>
		        </div>
		      </div>
		    </div>
		  `;
		},

		tplMyPlayerArea(player) {
		  return `
		    <div class='player-container my-container' style='border-color:#${player.color}'>
		      <div id="player-name-${player.id}" class='player-name' style='color:#${player.color}'>You</div>
		      <div class='player-area'>
		        <div class='player-left' id="player-left-${player.id}">
			        <span class="player-name"><i class="fa fa-arrow-left"></i></span>
		        	<div id="player-left-${player.id}-slide" class="slide-target"></div>
		        </div>
		        <div>
		          <div id="player-bank-area-${player.id}"></div>
		          <div class='player-exhausted' id="player-exhausted-${player.id}"></div>
		        </div>
		        <div class='player-right' id="player-right-${player.id}">
			        <span class="player-name"><i class="fa fa-arrow-right"></i></span>
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
		      <div class="player-supply-wrap">
			      <div class="player-supply">
			      	  <div id="turnip-supply-${player.id}-target" class="turnip-supply-target"></div>
				      ${supply}
			      </div>
		      </div>
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

		tplVictorySmall(args){
		  return `
		    <div id="t_${args.type}_${args.player.id}" class="turnip-wrapper">
			    <div class="token token-${args.type}"></div>
		    </div>
		  `;
		},

		tplTurnipSupply(player){
		  return `
		    <div id="turnip-supply-${player.id}" class="token token-turnip">${player.supply}</div>
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
		         <div id='player-hand-${player.id}' class='player-hand'>
		         	<div id="next-card-target" class="empty-slide-target"></div>
		         </div>
		    </div>
		  `;

		},

		tplCard(card) {
		  return `
		    <div id="card_${card.id}" class='card-wrapper card-click-target'>
		    	<div class='card-border'>
				    <div class='card tooltip-target card_${card.name}' data-id='${card.id}' data-name='${card.name}'></div>
			    </div>
		    </div>
		  `;
		},

		tplCardTooltip(card) {
		  return `
		    <div id="card_tooltip_${card.id}" class='card-tooltip-wrapper'>
			    <div class='card-tooltip card_${card.name}'></div>
		    </div>
		  `;
		},

		tplOtherCard(card){
		  return `
		    <div id="card_${card.id}" class='card-wrapper other-player-card'>
		    	<div class='card-border'>
				    <div class='card tooltip-target card_${card.name}' data-id='${card.id}' data-name='${card.name}'></div>
			    </div>
		    </div>
		  `;
		},

		tplPlaceHolder(args){
		  return `
		    <div id="placeholder-${args.side}-${args.player_id}" class='card-wrapper'>
		    	<div class='card-border'>
				    <div class='card card_back'></div>
			    </div>
		    </div>
		  `;
		},
	});
});