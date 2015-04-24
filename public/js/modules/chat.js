Modules.chat = {
	start: function () {
		Reveal.addEventListener('slidechanged', function( event ) {
			Modules.chat.checkCurrentSlide(event.currentSlide);
		});

		$('.chat-slide .button').on('tap click', function(event) {
			event.preventDefault();
			Modules.chat.sendMessage();
		});

		$('.chat-slide input').on('keypress', function(e) {
		    if(e.which == 13) {
		        Modules.chat.sendMessage();
		    }
		});

		ratchet.on(1, this.callback);

		Modules.chat.checkCurrentSlide(Reveal.getCurrentSlide());
	},

	callback: function(data) {
		if (data.type == 'chat') {
			$('.chat-slide .messages').prepend('<p><strong>' + data.nickname + '</strong>: ' + data.message + '</p>');
		}
	},

	checkCurrentSlide: function(currentSlide) {
		if ($(currentSlide).hasClass('chat-slide')) {
			if (!Modules.chat.initialized) {
				Modules.chat.init();
			}
		} else {
			if (Modules.chat.initialized) {
				Modules.chat.initialized = false;
			}
		}
	},

	initialized: false,

	init: function() {
		Modules.chat.initialized = true;
	},

	sendMessage: function() {
		var message = $('.chat-slide input[type="text"]').val();

		if (message != '') {
			ratchet.emit('chat', {message: message});
			$('.chat-slide .messages').prepend('<p class="me"><strong>[EU]</strong>: ' + message + '</p>');
			$('.chat-slide input[type="text"]').val('');
			Modules.sound.horn();
		}
	}
}