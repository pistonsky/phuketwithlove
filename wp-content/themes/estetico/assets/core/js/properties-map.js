/**
 * 
 */
var PropertiesMap = (function($) {

	var cls = function(containerID, options) {

		var map = null;

		var bounds = null;
		
		var self = this;

		var settings = {
			zoom : 12,
			fitBounds : true
		};

		var lastInfoWindow = null;

		var lastInfoWindowPlace = null;

		var placeMarkers = {};

		var requestedPlaceTypes = [];

		var typesAvailable = {
			'hospital' 	: ['hospital', 'health', 'dentist', 'doctor', 'pharmacy', 'physiotherapist', 'spa'],
			'school' 	: ['school', 'university'],
			'sport' 	: ['gym', 'stadium', 'bowling_alley'],
			'transit'	: ['bus_station', 'train_station', 'subway_station', 'taxi_stand'],
			'store'		: ['bicycle_store', 'book_store', 'clothing_store', 'convenience_store', 'department_store', 'electronics_store', 'furniture_store', 'grocery_or_supermarket', 'hardware_store', 'home_goods_store', 'jewelry_store', 'liquor_store', 'pet_store', 'shoe_store', 'shopping_mall', 'store'],
			'worship'	: ['church', 'mosque', 'synagogue', 'hindu_temple', 'place_of_worship'],
			'service'	: ['bank', 'accounting', 'car_wash', 'beauty_salon', 'city_hall', 'courthouse', 'electrician', 'embassy', 'finance', 'fire_station', 'gas_station', 'hair_care', 'florist', 'general_contractor', 'laundry', 'lawyer', 'library', 'local_government_office', 'locksmith', 'painter', 'plumber', 'police', 'post_office', 'roofing_contractor', 'veterinary_care'],
			'food'		: ['bar', 'cafe', 'food', 'bakery', 'night_club'],
			'entertainment' : ['movie_theater', 'casino', 'museum', 'amusement_park', 'aquarium', 'art_gallery', 'zoo']
		}

		var refreshPlaceMarkersAfterZoomChanged = true;

		this.addStreetView = function(markerOptions) {
			var latLng = new google.maps.LatLng(markerOptions.lat, markerOptions.lng);

			var panoramaOptions = {
				position : latLng,
				pov : {
					heading : 10,
					pitch : 10
				}
			}

			var dom = $('#properties-street-view');

			panoramaOptions.pov = dom.data('pov');

			var panorama = new google.maps.StreetViewPanorama(dom.get(0), panoramaOptions);

		};

		this.addMarker = function(markerOptions) {

			if(!markerOptions.lat || ! markerOptions.lng) {
				return false;
			}
			
			var latLng = new google.maps.LatLng(markerOptions.lat, markerOptions.lng);
			
			var marker = new google.maps.Marker({
				position: latLng,
				map: this.map,
				icon : _template_url + '/assets/core/img/marker' + ( markerOptions.rent ? '_rent' : '' ) + '.png'
			});

			var infowindow = new google.maps.InfoWindow({
				content : 
					'<div class="infowindow">' 
						+ '<div class="placeinfo">' + markerOptions.title + '</div>'
						+ '<div class="placeaddress">' + markerOptions.address + '</div>' 
						+ (markerOptions.photo && markerOptions.photo != '' ? '<img src="' + markerOptions.photo + '">' : '')
						+ (markerOptions.url ? '<div class="details-url"><a href="' + markerOptions.url + '">' + locale['View details'] + '</a></div>' : '')
					+ '</div>'
			});

			marker.infowindow = infowindow;

			google.maps.event.addListener(marker, 'click', function() {
				
				if(lastInfoWindow) {
					lastInfoWindow.close();
				}

				this.infowindow.open(self.map, marker);

				lastInfoWindow = this.infowindow;
			});

			if( settings.fitBounds ) {
				this.bounds.extend(latLng);

				this.map.fitBounds(this.bounds);
			} else {

				this.map.setCenter(marker.getPosition());
			}
		}

		this.createMarker = function(markerData, placeType) {
			var self = this;

			var image = {
				url : markerData.icon,
				size : new google.maps.Size(71, 71),
				scaledSize: new google.maps.Size(20, 20)
			};

			var marker = new google.maps.Marker({
				position : markerData.geometry.location,
				map : this.map,
				icon : image
			});

			var photoDiv = '';

			if(markerData.photos !== undefined) {
				photoDiv = '<div class="photo"><img src="' + markerData.photos[0].getUrl({'maxWidth' : 100, 'maxHeight' : 100}) + '" alt="' + markerData.name + '"></div>';
			}

			var infowindow = new google.maps.InfoWindow({
				content : 
					'<div class="infowindow">' 
						+ '<div class="placeinfo">' + markerData.name + '</div>' 
						+ '<div class="placeaddress">' + markerData.vicinity + '</div>' 
						+ photoDiv
					+ '</div>',
				pixelOffset : new google.maps.Size(-25, 0)
			});

			marker.infowindow = infowindow;

			google.maps.event.addListener(marker, 'click', function() {

				if(lastInfoWindowPlace) {
					lastInfoWindowPlace.close();
				}

				this.infowindow.open(self.map, marker);

				lastInfoWindowPlace = this.infowindow;
			});

			if(placeMarkers[placeType] == undefined) {
				placeMarkers[placeType] = [];
			}

			placeMarkers[placeType].push(marker);
		}

		this.removePlaceMarkers = function(placeType) {

			if(placeMarkers[placeType] != undefined) {
				for(var i = 0; i < placeMarkers[placeType].length; i++) {
					placeMarkers[placeType][i].setMap(null);
				}
			}
		}

		this.removeAllPlaceMarkers = function() {

			for(var placeType in placeMarkers) {
				this.removePlaceMarkers(placeType);
			}
		}

		this.addPlaceMarkers = function() {

			for(var i = 0; i < requestedPlaceTypes.length; i++) {
				this.requestPlaces(requestedPlaceTypes[i]);
			}
		}

		this.add = function(list) {

			for(var i = 0, n = list.length; i < n; i += 1) {

				this.addMarker(list[i]);
			}
		}

		this.initialize = function() {

			var self = this;

			settings = $.extend(settings, options);

			var mapOptions = {
				zoom: settings.zoom,
				center: new google.maps.LatLng(0, 0),
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};

			var mapContainer = this.getMapContainer();
			
			this.map = new google.maps.Map(mapContainer, mapOptions);

			var mainMainEventCallback = function() {
				
				if(refreshPlaceMarkersAfterZoomChanged) {
					self.removeAllPlaceMarkers();
					self.addPlaceMarkers();
				}
			}

			google.maps.event.addListener(this.map, 'zoom_changed', mainMainEventCallback);
			//google.maps.event.addListener(this.map, 'drag', mainMainEventCallback);

			this.bounds = new google.maps.LatLngBounds();

			this.bindEvents();
		}

		this.bindEvents = function() {

			var mapContainer = this.getMapContainer(),
				mapPack = $(mapContainer).parents('.properties-map-pack').eq(0),
				self = this;

			mapPack.find('.trigger-view-full-screen').on('click', function() {

				mapPack.find('.move-me').each(function() {

					if($(this).parent().hasClass('move-me-ph') === false) {
						$(this).wrap('<div class="move-me-ph"></div>');
					}

					$('#properties-map-fullscreen .put-it-here').append(this);

					var fullscreenMapHeight = $(window).height();
					fullscreenMapHeight -= 220;

					$('#properties-map-fullscreen .properties-map-instance').height(fullscreenMapHeight);
				});

				
				$('#properties-map-fullscreen').show();

				 google.maps.event.trigger(self.map, 'resize');

				return false;
			});

			$('#properties-map-fullscreen .close').on('click', function() {

				$('#properties-map-fullscreen .move-me').each(function(index) {
					$(this).find('.properties-map-instance').height('');
					mapPack.find('.move-me-ph').eq(index).append(this);
				});

				$('#properties-map-fullscreen').hide();

				google.maps.event.trigger(self.map, 'resize');

				return false;
			});
		}

		this.getMapContainer = function() {
			var mapContainer = typeof containerID == 'string' ? document.getElementById(containerID) : containerID;

			return mapContainer;
		}

		// Initialize local places
		this.localInitialize = function() {

			this.bindEventsLocal();
		}

		this.removeRequestedPlaceType = function(placeType) {
			var index = requestedPlaceTypes.indexOf(placeType);
			if(index >= -1) {
				requestedPlaceTypes.splice(index, 1);
			}
		}

		this.saveRequestedPlaceType = function(placeType) {

			requestedPlaceTypes.push(placeType);
		}

		this.requestPlaces = function(placeType) {
			var request = {
				bounds 	: this.map.getBounds(),
				types 	: typesAvailable[placeType]
			}

			var service = new google.maps.places.PlacesService(this.map);
			var self = this;

			service.nearbySearch(request, function(results, status) {
				self.requestPlacesCallback(results, status, placeType);
			});
		}

		this.requestPlacesCallback = function(results, status, placeType) {
			if(status == google.maps.places.PlacesServiceStatus.OK) {

				for(var i = 0; i < results.length; i++) {
					this.createMarker(results[i], placeType);
				}
			}
		}

		this.bindEventsLocal = function() {

			var mapContainer = this.getMapContainer();
			var self = this;

			$(mapContainer).parents('.properties-map-pack').eq(0).find('.trigger-local-places').on('click', function() {

				var requestedType = $(this).data('type');

				// Requested types does not exists
				if(typesAvailable[requestedType] === undefined) {
					return false;
				}

				// Remove places from the ma[]
				if($(this).prop('checked') === false) {

					self.removePlaceMarkers(requestedType);

					self.removeRequestedPlaceType(requestedType);

				// Add places to map
				} else {

					self.saveRequestedPlaceType(requestedType);

					self.requestPlaces(requestedType);
				}
			});
		}

		this.initialize();
	}

	return cls;

})(jQuery, window, undefined);