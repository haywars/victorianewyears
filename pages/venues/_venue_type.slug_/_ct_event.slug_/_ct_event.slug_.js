
$(document).ready(function() {
	$('#profile-content').livequery(function() {
		var do_stick = {
			w : $(window),
			div : $('#quick_jump ul'),
			top_div: $('#quick_jump'),
			bottom_div : $('#quick_jump_after'),
			window_top : function() { return do_stick.w.scrollTop() + 10; },
			div_top : function() { return do_stick.top_div.offset().top; },
			div_bottom : function() { return do_stick.bottom_div.offset().top - do_stick.div.height(); },
			relocate : function() {
				var window_top = do_stick.window_top(),
					is_in = (window_top > do_stick.div_top()),
					is_after = (window_top > do_stick.div_bottom());
				if (is_after) {
					do_stick.div.removeClass('stick').addClass('bottom-stick');
				} else if (is_in) {
					do_stick.div.removeClass('bottom-stick').addClass('stick');
				} else {
					do_stick.div.removeClass('stick bottom-stick');
				}
			}	
		};
		if (!!$('.carousel').length) $('.carousel').jcarousel();
		do_stick.w.scroll(do_stick.relocate);
		do_stick.relocate();
	});
	
	
	// Cufon.replace('.cufon');

	$('#map').livequery(function() {
		
		if (typeof EventBars == 'undefined') return;

		var h = $('#bars_list').height() - 10;
		if (h> 400) {
			$('#map_cont').height(h);
		}

		function getMarkerIcon(type, num) {
			return 'http://www.google.com/intl/en_ALL/mapfiles/marker' + type + num + '.png';
		}

		function makeMarker(map, item, icon) {
			return new google.maps.Marker({
				map: map,
				draggable: false,
				animation: google.maps.Animation.DROP,
				position: item.latlng,
				title: item.title,
				icon: icon
			});
		}

		var map = GMAP('#map', function() {
			
			return EventBars.map(function(r) {
				return {
					title: r.venue_name,
					is_registration_point: r.is_registration_point,
					html: r.address,
					latlng: new google.maps.LatLng(r.lat, r.lng)
				};
			});

		},  { 
			
			setMarker: function(i, item) {
				console.log(item);
				var type = (item.is_registration_point == 1) ? '_yellow' : '_green',
					n = String.fromCharCode(65 + i),
					icon = getMarkerIcon(type, n),
					marker = makeMarker(this.data.map, item, icon);

				this.data.mapBounds.extend(marker.position);
				this.methods.setMarkerClick(marker, item);

			} 

		}).init();

	});
	
	$('#venue_map').livequery(function() {
		var $venue_map = $(this);
		GMAP($venue_map, function() {
			return [ {
				title: $venue_map.attr('venue_name'),
				html: $venue_map.attr('address'),
				latlng: new google.maps.LatLng($venue_map.attr('lat'), $venue_map.attr('lng'))
			} ];
		}).init();
	});

	$('.quick_jump').live('click', function(e) {
		e.preventDefault();
		var qj = $('#quick_jump').closest('.section').position().top;
		var to = $(this).attr('href');
		to = to.split('#')[1];
		var $to = $('div[name=' + to + ']');
		$('html, body').animate({ scrollTop: $to.position().top + qj }, 200);
		return false;
	});

	$('.back_to_top').live('click', function(e) {
		e.preventDefault();
		$('html, body').animate({scrollTop: 0}, 200);
		return false;
	});

	

	$("#show_event_flyout").click(function() {
		$('#show_event_flyout').toggleClass('down up');
		$('.more_events_flyout').slideToggle('fast', function() {
			
  		});
	});
	
});
