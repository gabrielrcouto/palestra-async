ratchet = {
	connect: function(url) {
		console.log('WebSocket conectando...');

		ratchet.url = url;

		//Socket is closed?
		if (!ratchet.connecting && (ratchet.connection == null || (ratchet.connecting.readyState !== 0 && ratchet.connecting.readyState !== 1))) {
			ratchet.connection = new WebSocket('ws://' + url);
			ratchet.connection.onopen = ratchet.onOpen;
			ratchet.connection.onmessage = ratchet.onMessage;
			ratchet.connection.onclose = ratchet.onClose;
			ratchet.connection.onerror = ratchet.onError;
			ratchet.connecting = true;
		}

		return ratchet;
	},

	connection: null,

	connecting: false,

	connected: false,

	emit: function(event, data) {
		if (ratchet.connected) {
			data.type = event;
			ratchet.connection.send(JSON.stringify(data));
		} else {
			setTimeout(function() {
				ratchet.emit(event, data);
			}, 1000);
		}
	},

	on: function(id, callback) {
		ratchet.onCallback.push(callback);
	},

	onCallback: [],

	onOpen: function(e) {
		console.log('WebSocket conectado!');
		ratchet.connected = true;
	},

	onMessage: function(e) {
		var data = $.parseJSON(e.data);

		$.each(ratchet.onCallback, function(index, callback) {
			callback(data);
		});
	},

	onClose: function(e) {
		ratchet.connecting = false;
		
		setTimeout(function() {
			ratchet.connect(ratchet.url);	
		}, 5000);
	},

	onError: function(e) {
		ratchet.connecting = false;

		setTimeout(function() {
			ratchet.connect(ratchet.url);	
		}, 5000);
	},

	url: ''
}