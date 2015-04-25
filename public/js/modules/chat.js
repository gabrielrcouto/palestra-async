Modules.chat = {
	start: function () {
		Reveal.addEventListener('slidechanged', function( event ) {
			Modules.chat.checkCurrentSlide(event.currentSlide);
		});

		$('div.chat .button').on('tap click', function(event) {
			event.preventDefault();
			Modules.chat.sendMessage();
		});

		$('div.chat input').on('keypress', function(e) {
		    if(e.which == 13) {
		        Modules.chat.sendMessage();
		    }
		});

		ratchet.on(1, this.callback);

		Modules.chat.checkCurrentSlide(Reveal.getCurrentSlide());
	},

	callback: function(data) {
		if (data.type == 'chat') {
			$('div.chat .messages').prepend('<p><strong>' + data.nickname + '</strong>: ' + data.message + '</p>');
		}
	},

	checkCurrentSlide: function(currentSlide) {
		if ($(currentSlide).hasClass('chat-slide') && $('body').attr('data-mode') != 'presenter') {
			if (!Modules.chat.initialized) {
				Modules.chat.init();
				$('div.chat').show();
			}
		} else {
			if (Modules.chat.initialized) {
				Modules.chat.initialized = false;
				$('div.chat').hide();
			}
		}
	},

	initialized: false,

	init: function() {
		Modules.chat.initialized = true;
	},

	sendMessage: function() {
		var message = $('div.chat input[type="text"]').val();

		if (message != '') {
			ratchet.emit('chat', {message: message});
			$('div.chat .messages').prepend('<p class="me"><strong>[EU]</strong>: ' + message + '</p>');
			$('div.chat input[type="text"]').val('');
			Modules.sound.horn();
		}
	}
}