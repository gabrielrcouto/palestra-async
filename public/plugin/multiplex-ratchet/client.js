(function() {
	var multiplex = Reveal.getConfig().multiplex;
	var socketId = multiplex.id;
	var socket = ratchet.connect(multiplex.url);

	socket.on(multiplex.id, function(data) {
		// ignore data from sockets that aren't ours
		// if (data.socketId !== socketId) { return; }
		if( window.location.host === 'localhost:1947' ) return;

		if (data.type == 'slide') {
			Reveal.slide(data.indexh, data.indexv, data.indexf, 'remote');
		}
	});
}());
