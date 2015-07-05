(function($) {
	
	"use strict";

	function initStreetViewPovAdjust() {
		$('.google-street-view-pov-adjust').each(function() {

			if(typeof google == 'undefined') {
				return;
			}

			var dom = $(this).get(0),
				that = this;

			var lat = $('#' + _site_name_prefix + 'latitude_control').val();
			var lng = $('#' + _site_name_prefix + 'longitude_control').val();

			var latLng = new google.maps.LatLng(lat, lng);

			var input = $(this).next();

			var pov = {
				pitch : 10,
				heading : 10
			};

			try {
				pov = JSON.parse(input.val());
			} catch(e) {
				console.log(e);
			}

			var panoramaOptions = {
				position : latLng
			}

			var panorama = new google.maps.StreetViewPanorama(dom, panoramaOptions);

			setInterval(function() {
				if(panorama.getStatus() != 'OK') {
					input.val('ERROR');
				} else {
					input.val( JSON.stringify( panorama.getPov() ) );
				}
				console.log(panorama.getStatus());
			}, 1000);

			panorama.addListener('pov_changed', function(pov) {
				input.val( JSON.stringify( this.getPov() ) );
			});

		});
	}

	$(function() {

		initStreetViewPovAdjust();

		$('#' + _site_name_prefix + 'latitude_control, #' + _site_name_prefix + 'longitude_control').blur(function() {
			initStreetViewPovAdjust();
		});

		$('#' + _site_name_prefix + 'pets_allowed_control').click(function() {

			if($(this).prop('checked')) {

				$('#' + _site_name_prefix + 'pet_types').show();
			} else {

				$('#' + _site_name_prefix + 'pet_types').hide();
			}
		});

		if($('#' + _site_name_prefix + 'pets_allowed_control').prop('checked') == true) {
			$('#' + _site_name_prefix + 'pet_types').show();
		} else {
			$('#' + _site_name_prefix + 'pet_types').hide();
		}

		$('.set-featured').click( function(e) {
			e.preventDefault();

			var _this = this;

			$.get( _template_url + '/bootstrap/services/set_featured.php', { featured : $(this).data('set-featured'), post_id : $(this).data('post-id')}, function(response) {

				if(response.status == 'ok') {

					if($(_this).data('set-featured') == 'on') {
						$(_this).html('Yes');
						$(_this).data('set-featured', 'off');
					} else {
						$(_this).html('No');
						$(_this).data('set-featured', 'on');
					}
				}

			});

			return false;
		});
		
		$('.find-coordinates').click( function(e) {

			e.preventDefault();

			var addressParts = [];
			addressParts.push(jQuery('#estetico_country_control').val());
			addressParts.push(jQuery('#estetico_state_control').val());
			addressParts.push(jQuery('#estetico_county_control').val());
			addressParts.push(jQuery('#estetico_city_control').val());
			addressParts.push(jQuery('#estetico_street_address_control').val());
			addressParts.push(jQuery('#estetico_street_number_control').val());
			addressParts.push(jQuery('#estetico_postal_code_control').val());

			var address = addressParts.join(',');

			$.get( _template_url + '/bootstrap/services/geo.php', { address : address }, function( response ) {

				if( response.status == 'OK' ) {

					if( response.results[0] ) {

						var lat = response.results[0].geometry['location'].lat,
							lng = response.results[0].geometry['location'].lng;

						$('input[name="' + _site_name_prefix + 'latitude"]').val( lat );
						$('input[name="' + _site_name_prefix + 'longitude"]').val( lng );

						initStreetViewPovAdjust();
					}
				}

			}, 'json' );

			return false;
		});

		$('.suggest-input').each(function() {
			$(this).suggest(_template_url + '/bootstrap/services/suggestion.php?type=' + $(this).prop('name').replace(_site_name_prefix, '') , { delay: 500, minchars: 2, multiple: false, multipleSep: postL10n.comma + ' ' });
		});

		// Hide Postbox from viewing 
		$('.hide-postbox').parents('.postbox').hide();
	});

})(jQuery);