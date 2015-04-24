Modules.poll = {
	start: function() {
		//Listener dos botões
		$('.poll .button-level').on('tap click', function(event) {
			event.preventDefault();

			var poll = $(this).closest('.poll');

			if (!$(poll).hasClass('votado')) {
				ratchet.emit('poll', {number: $(poll).attr('data-number'), value: $(this).attr('data-value')});
				$(poll).addClass('votado');
			}
		});

		$('.poll .button-level span').on('tap click', function(event) {
			event.preventDefault();
		});

		//Listener do socket
		ratchet.on(1, this.callback);
	},

	callback: function(data) {
		if (data.type == 'poll-result') {
			var poll = $('.poll[data-number="' + data.number + '"]');

			$.each(data.votes, function(index, quantity) {
				poll.find('.button-level[data-value="' + index + '"]').find('span b').html(quantity);
			});

			$.each(data.percentages, function(index, quantity) {
				poll.find('.button-level[data-value="' + index + '"]').find('.level').height(quantity + '%');
			});
		}
	},
}