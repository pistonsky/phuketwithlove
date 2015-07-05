/**
 * Properties filter
 */
(function($) {

	$('.property-location-filter').autocomplete({
		source : _template_url + '/bootstrap/services/geo.php?ref=filter',
		minLength : 2,
		select : function( event, ui ) {
			$('.property-location-latitude').val(ui.item.geometry.location.lat);
			$('.property-location-longitude').val(ui.item.geometry.location.lng);
		}
	});


	/**
	 * Create a URL for the customer selected filter criteria
	 * 
	 * @return 
	 */
	function generateFilterURL() {

		var base_href = _properties_url,
			query_string = [],
			counter = 1,
			features = [],
			types = [];

		$('.search-filters .features .feature-holder').each(function(index) {
			if( this.checked ) {
				features.push( $(this).val() );
			}
		});

		if(features.length > 0) {
			query_string.push('feature=' + features.join(','));
		}

		$('.search-filters .types .type-holder').each(function(index) {
			if( this.checked ) {
				types.push( $(this).val() );
			}
		});

		if(types.length > 0) {
			query_string.push('type=' + types.join(','));
		}

		$('.search-filters .slider').each(function() {

			var slider_name = $(this).data('name');
			var values = $(this).slider('option', 'values');

			var min_value = parseInt( $(this).next().find('.value.min').text(), 10 );
			var max_value = parseInt( $(this).next().find('.value.max').text(), 10 );

			// Add to query string only if customer moved the sliders.
			if(min_value != values[0] || max_value != values[1]) {
				query_string.push( slider_name + '=' + values.join(',') );
			}
		});

		var location = $('.property-location-filter').val();
		if(location && location != "") {

			query_string.push( 'location=' + location );
		}

		var lat = $('.property-location-latitude').val();
		var lng = $('.property-location-longitude').val();

		if(location != "" && lat && lng && lat != '' && lng != '') {

			query_string.push( 'lat=' + lat + '&lng=' + lng );
		}

		var distance = $('select[name=distance]').val();
		if(distance && distance != "") {

			query_string.push( 'distance=' + distance );
		}

		var beds = $('select[name=beds]').val();
		if(beds && beds != "") {

			query_string.push( 'beds=' + beds );
		}

		var city = $('select[name=city]').val();
		if(city && city != "") {

			query_string.push( 'city=' + city );
		}

		var keywords = $('.property-filter-keywords').val();
		if(keywords && keywords != '') {

 			query_string.push( 'keywords=' + keywords );
 		}

 		var for_sale_rent = $('input[name="for_sale_rent"]').filter(':checked').val();
		if(for_sale_rent && for_sale_rent != '') {

 			query_string.push( 'for_sale_rent=' + for_sale_rent );
 		}

 		var pets_allowed = $('input[name="pets_allowed"]').filter(':checked').val();
 		if(pets_allowed && pets_allowed != '') {

 			query_string.push('pets_allowed=' + pets_allowed);
 		}
        
        var property_status = $('select[name=property_status]').val();
 		if(property_status && property_status != '') {

                query_string.push('property_status=' + property_status);
 		}

 		if(window.location.search && window.location.search != '') {
 			if(window.location.search.indexOf('list_style') > -1) {
 				var param_pairs = window.location.search.slice(1).split('&');
 				for(var i = 0, n = param_pairs.length; i < n; i++) {
 					var key_value = param_pairs[i].split('='),
 						key = key_value[0],
 						value = key_value[1];

 					if(key == 'list_style') {
 						query_string.push('list_style='+value);
 					}
 				}
 			}
 		}

		return base_href + (query_string.length > 0 ? '?' + query_string.join('&') : '');
	}

	$('.filter-properties-btn').click(function() {

		var url = generateFilterURL();

		window.location.href = url;

		return false;
	});

	$('.search-filters .slider').each(function() {

		var min 		= $(this).data('min'),
			max 		= $(this).data('max'),
			step 		= $(this).data('step'),
			min_value 	= $(this).data('min-value'),
			max_value 	= $(this).data('max-value');

		if( ! min_value ) {
			min_value = min;
		}

		if( ! max_value ) {
			max_value = max;
		}

		$(this).slider({
			range : true,
			min : min,
			max : max,
			step : step,
			values : [min_value, max_value],
			slide : function(event, ui) {

				$(this).next('.values').find('.min-value').html(ui.values[0]);
				$(this).next('.values').find('.max-value').html(ui.values[1]);
			}
		});
	});

	$('.features li, .types li').click(function(e) {

		if(e.target.nodeName != 'INPUT') {
		
			e.stopPropagation();

			var input = $(this).find('input');

			input.prop('checked', !input.prop('checked'));

			return false;
		}
	});

})(jQuery)