jQuery(function($) {

	var yt = {
		name: 'YouTube',
		getCode: function(url) {
			var parse = url.match(/https?:\/\/www.youtube.com\/watch\?.*v=([^&]*)/);
			if (parse && parse[1]) {
				return parse[1];
			}
		},
		getData: function(code, callback) {
			$.ajax({
				dataType: 'jsonp',
				url: '//gdata.youtube.com/feeds/api/videos/' + code + '?v=2&alt=jsonc',
				success: function(data) {
					if (data.data) {
						callback(null, {
							'title': data.data.title,
							'thumbnail': data.data.thumbnail.hqDefault
						});
					}
					else {
						callback('Failed to get title');
					}
				},
				error: function() {
					callback('Failed to get title');
				}
			});
		},
		getUrl: function(code) {
			return 'https://www.youtube.com/watch?v=' + code;
		}
	};
	var vimeo = {
		name: 'Vimeo',
		getCode: function(url) {
			var parse = url.match(/https?:\/\/vimeo.com\/([0-9]+)/);
			if (parse && parse[1]) {
				return parse[1];
			}
		},
		getData: function(code, callback) {
			var status = null;
			$.ajax({
				dataType: 'jsonp',
				url: '//vimeo.com/api/v2/video/' + code + '.json',
				success: function(data) {
					if (status == 'error') {
						return;
					}
					status = 'success';
					callback(null, {
						'title': data[0].title,
						'thumbnail': data[0].thumbnail_large
					});
				}
			});
			setTimeout(function() {
				if (status == 'success') {
					return;
				}
				status = 'error';
				callback('Failed to get title');
			}, 2000);
		},
		getUrl: function(code) {
			return 'http://vimeo.com/' + code;
		}
	};

	var services = [yt, vimeo];

	function getService(url) {
		for (var i = 0; i < services.length; i++) {
			var service = services[i];
			var code = service.getCode(url);
			if (code) {
				return service;
			}
		}
		return null;
	}

	function updateStatus(status, val) {
		status.removeClass('empty noservice error success');
		status.addClass('loading');
		status.text('loading');

		if (!val || val.match(/^\s*$/)) {
			status.removeClass('loading noservice error success');
			status.addClass('empty');
			status.text('');
			return;
		}

		var service = getService(val);
		if (!service) {
			status.removeClass('empty loading error success');
			status.addClass('noservice');
			status.text('Video not found');
			return;
		}
		var code = service.getCode(val);
		service.getData(code, function(err, data) {
			if (err) {
				status.removeClass('empty loading noservice success');
				status.addClass('error');
				status.text('Error getting data. Are you sure this is a valid ' + service.name + ' URL?');

				status.closest('.videolink').find('[data-title]').val('');
				status.closest('.videolink').find('[data-thumbnail]').val('');
			}
			else {
				var title = data.title;
				var thumbnail = data.thumbnail;

				status.removeClass('empty loading noservice error');
				status.addClass('success');
				status.empty();
				$('<a>').attr('href', service.getUrl(code)).attr('target', '_blank').text(title).appendTo(status);

				status.closest('.videolink').find('[data-title]').val(title);
				status.closest('.videolink').find('[data-thumbnail]').val(thumbnail);
			}
		});
	}

	function initializeElement(videolink) {
		if (videolink.data('videolink-isinitialized')) {
			return;
		}

		videolink.data('videolink-isinitialized', true);

		var input = videolink.find('input');
		var status = videolink.find('.status');
		if (!status.length) {
			status = $('<div>').addClass('status').appendTo(videolink);
		}
		input.on('change', function() {
			var val = input.val();
			updateStatus(status, val);
		});

		var val = input.val();
		updateStatus(status, val);
	}

	$('.videolink').each(function() {
		initializeElement($(this));
	});

	if (typeof(window.Matrix) !== 'undefined') {
		Matrix.bind('videolink', 'display', function(cell) {
			// When Matrix addes a new videolink, initialize it.
			var videolink = cell.dom.$inputs.closest('.videolink');
			initializeElement(videolink);
		});

		Matrix.bind('videolink', 'remove', function(cell) {
			// Because videolink inputs have a type of 'url', if a person
			// removes a videolink from Matrix that does not have a valid
			// value, the browser will refuse to submit it (because it isn't a
			// valid URL), but that won't be visisble to the user. So, when a
			// user removes a videolink, just clear the value, so the browser
			// doesn't do that.
			var videolink = cell.dom.$inputs.closest('.videolink');
			videolink.find('input').val('');
		});
	}

	if (typeof(window.Grid) !== 'undefined') {
		Grid.bind('videolink', 'display', function(cell) {
			// When Matrix addes a new videolink, initialize it.
			var videolink = $(cell).find('.videolink');
			initializeElement(videolink);
		});

		Grid.bind('videolink', 'remove', function(cell) {
			// Because videolink inputs have a type of 'url', if a person
			// removes a videolink from Grid that does not have a valid value,
			// the browser will refuse to submit it (because it isn't a valid
			// URL), but that won't be visisble to the user. So, when a user
			// removes a videolink, just clear the value, so the browser
			// doesn't do that.
			var videolink = $(cell).find('.videolink');
			videolink.find('input').val('');
		});
	}
});
