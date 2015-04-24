Modules.horn = {
	start: function() {
		//Listener do botão
		$('.horn.button').on('tap click', function(event) {
			event.preventDefault();

			Modules.sound.horn();

			ratchet.emit('horn', {});
			Modules.horn.countMe++;
			Modules.horn.renderCounter();
		});

		$('.horn.button span').on('tap click', function(event) {
			event.preventDefault();
		});

		//Listener do socket
		ratchet.on(1, this.callback);
	},

	countMe: 0,

	countOthers: 0,

	callback: function(data) {
		if (data.type == 'horn') {
			Modules.sound.horn();
			Modules.horn.countOthers++;
			Modules.horn.renderCounter();
		}
	},

	renderCounter: function() {
		$('.horn.counter').html(Modules.horn.countMe + '/' + Modules.horn.countOthers);
	}
}