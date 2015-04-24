Modules.counter = {
	start: function () {
		ratchet.on(1, this.callback);
	},

	callback: function(data) {
		if (data.type == 'counter') {
			$('.users-counter').html(data.number);
		}
	},
}