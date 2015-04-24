Modules.jequiti = {
	start: function () {
		Reveal.addEventListener('slidechanged', function( event ) {
			Modules.jequiti.checkCurrentSlide(event.currentSlide);
		});

		Modules.jequiti.checkCurrentSlide(Reveal.getCurrentSlide());
	},

	checkCurrentSlide: function(currentSlide) {
		if ($(currentSlide).hasClass('jequiti')) {
			setTimeout(function() {
				$('div.jequiti-background').show();

				setTimeout(function() {
					$('div.jequiti-background').hide();
				}, 500);	
			}, 2000);
		}
	}
}