/*! http://mths.be/placeholder v2.0.7 by @mathias */
;(function(window, document, $) {

	// Opera Mini v7 doesn’t support placeholder although its DOM seems to indicate so
	var isOperaMini = Object.prototype.toString.call(window.operamini) == '[object OperaMini]';
	var isInputSupported = 'placeholder' in document.createElement('input') && !isOperaMini;
	var isTextareaSupported = 'placeholder' in document.createElement('textarea') && !isOperaMini;
	var prototype = $.fn;
	var valHooks = $.valHooks;
	var propHooks = $.propHooks;
	var hooks;
	var placeholder;

	if (isInputSupported && isTextareaSupported) {

		placeholder = prototype.placeholder = function() {
			return this;
		};

		placeholder.input = placeholder.textarea = true;

	} else {

		placeholder = prototype.placeholder = function() {
			var $this = this;
			$this
				.filter((isInputSupported ? 'textarea' : ':input') + '[placeholder]')
				.not('.placeholder')
				.bind({
					'focus.placeholder': clearPlaceholder,
					'blur.placeholder': setPlaceholder
				})
				.data('placeholder-enabled', true)
				.trigger('blur.placeholder');
			return $this;
		};

		placeholder.input = isInputSupported;
		placeholder.textarea = isTextareaSupported;

		hooks = {
			'get': function(element) {
				var $element = $(element);

				var $passwordInput = $element.data('placeholder-password');
				if ($passwordInput) {
					return $passwordInput[0].value;
				}

				return $element.data('placeholder-enabled') && $element.hasClass('placeholder') ? '' : element.value;
			},
			'set': function(element, value) {
				var $element = $(element);

				var $passwordInput = $element.data('placeholder-password');
				if ($passwordInput) {
					return $passwordInput[0].value = value;
				}

				if (!$element.data('placeholder-enabled')) {
					return element.value = value;
				}
				if (value == '') {
					element.value = value;
					// Issue #56: Setting the placeholder causes problems if the element continues to have focus.
					if (element != safeActiveElement()) {
						// We can't use `triggerHandler` here because of dummy text/password inputs :(
						setPlaceholder.call(element);
					}
				} else if ($element.hasClass('placeholder')) {
					clearPlaceholder.call(element, true, value) || (element.value = value);
				} else {
					element.value = value;
				}
				// `set` can not return `undefined`; see http://jsapi.info/jquery/1.7.1/val#L2363
				return $element;
			}
		};

		if (!isInputSupported) {
			valHooks.input = hooks;
			propHooks.value = hooks;
		}
		if (!isTextareaSupported) {
			valHooks.textarea = hooks;
			propHooks.value = hooks;
		}

		$(function() {
			// Look for forms
			$(document).delegate('form', 'submit.placeholder', function() {
				// Clear the placeholder values so they don't get submitted
				var $inputs = $('.placeholder', this).each(clearPlaceholder);
				setTimeout(function() {
					$inputs.each(setPlaceholder);
				}, 10);
			});
		});

		// Clear placeholder values upon page reload
		$(window).bind('beforeunload.placeholder', function() {
			$('.placeholder').each(function() {
				this.value = '';
			});
		});

	}

	function args(elem) {
		// Return an object of element attributes
		var newAttrs = {};
		var rinlinejQuery = /^jQuery\d+$/;
		$.each(elem.attributes, function(i, attr) {
			if (attr.specified && !rinlinejQuery.test(attr.name)) {
				newAttrs[attr.name] = attr.value;
			}
		});
		return newAttrs;
	}

	function clearPlaceholder(event, value) {
		var input = this;
		var $input = $(input);
		if (input.value == $input.attr('placeholder') && $input.hasClass('placeholder')) {
			if ($input.data('placeholder-password')) {
				$input = $input.hide().next().show().attr('id', $input.removeAttr('id').data('placeholder-id'));
				// If `clearPlaceholder` was called from `$.valHooks.input.set`
				if (event === true) {
					return $input[0].value = value;
				}
				$input.focus();
			} else {
				input.value = '';
				$input.removeClass('placeholder');
				input == safeActiveElement() && input.select();
			}
		}
	}

	function setPlaceholder() {
		var $replacement;
		var input = this;
		var $input = $(input);
		var id = this.id;
		if (input.value == '') {
			if (input.type == 'password') {
				if (!$input.data('placeholder-textinput')) {
					try {
						$replacement = $input.clone().attr({ 'type': 'text' });
					} catch(e) {
						$replacement = $('<input>').attr($.extend(args(this), { 'type': 'text' }));
					}
					$replacement
						.removeAttr('name')
						.data({
							'placeholder-password': $input,
							'placeholder-id': id
						})
						.bind('focus.placeholder', clearPlaceholder);
					$input
						.data({
							'placeholder-textinput': $replacement,
							'placeholder-id': id
						})
						.before($replacement);
				}
				$input = $input.removeAttr('id').hide().prev().attr('id', id).show();
				// Note: `$input[0] != input` now!
			}
			$input.addClass('placeholder');
			$input[0].value = $input.attr('placeholder');
		} else {
			$input.removeClass('placeholder');
		}
	}

	function safeActiveElement() {
		// Avoid IE9 `document.activeElement` of death
		// https://github.com/mathiasbynens/jquery-placeholder/pull/99
		try {
			return document.activeElement;
		} catch (err) {}
	}

}(this, document, jQuery));

if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (searchElement, fromIndex) {
      if ( this === undefined || this === null ) {
        throw new TypeError( '"this" is null or not defined' );
      }

      var length = this.length >>> 0; // Hack to convert object.length to a UInt32

      fromIndex = +fromIndex || 0;

      if (Math.abs(fromIndex) === Infinity) {
        fromIndex = 0;
      }

      if (fromIndex < 0) {
        fromIndex += length;
        if (fromIndex < 0) {
          fromIndex = 0;
        }
      }

      for (;fromIndex < length; fromIndex++) {
        if (this[fromIndex] === searchElement) {
          return fromIndex;
        }
      }

      return -1;
    };
  }

/*! matchMedia() polyfill - Test a CSS media type/query in JS. Authors & copyright (c) 2012: Scott Jehl, Paul Irish, Nicholas Zakas. Dual MIT/BSD license */

window.matchMedia = window.matchMedia || (function( doc, undefined ) {

  "use strict";

  var bool,
      docElem = doc.documentElement,
      refNode = docElem.firstElementChild || docElem.firstChild,
      // fakeBody required for <FF4 when executed in <head>
      fakeBody = doc.createElement( "body" ),
      div = doc.createElement( "div" );

  div.id = "mq-test-1";
  div.style.cssText = "position:absolute;top:-100em";
  fakeBody.style.background = "none";
  fakeBody.appendChild(div);

  return function(q){

    div.innerHTML = "&shy;<style media=\"" + q + "\"> #mq-test-1 { width: 42px; }</style>";

    docElem.insertBefore( fakeBody, refNode );
    bool = div.offsetWidth === 42;
    docElem.removeChild( fakeBody );

    return {
      matches: bool,
      media: q
    };

  };

}( document ));

/*! Cookies.js - 0.3.1; Copyright (c) 2013, Scott Hamper; http://www.opensource.org/licenses/MIT */
(function(e){"use strict";var a=function(b,d,c){return 1===arguments.length?a.get(b):a.set(b,d,c)};a._document=document;a._navigator=navigator;a.defaults={path:"/"};a.get=function(b){a._cachedDocumentCookie!==a._document.cookie&&a._renewCache();return a._cache[b]};a.set=function(b,d,c){c=a._getExtendedOptions(c);c.expires=a._getExpiresDate(d===e?-1:c.expires);a._document.cookie=a._generateCookieString(b,d,c);return a};a.expire=function(b,d){return a.set(b,e,d)};a._getExtendedOptions=function(b){return{path:b&&
b.path||a.defaults.path,domain:b&&b.domain||a.defaults.domain,expires:b&&b.expires||a.defaults.expires,secure:b&&b.secure!==e?b.secure:a.defaults.secure}};a._isValidDate=function(b){return"[object Date]"===Object.prototype.toString.call(b)&&!isNaN(b.getTime())};a._getExpiresDate=function(b,d){d=d||new Date;switch(typeof b){case "number":b=new Date(d.getTime()+1E3*b);break;case "string":b=new Date(b)}if(b&&!a._isValidDate(b))throw Error("`expires` parameter cannot be converted to a valid Date instance");
return b};a._generateCookieString=function(b,a,c){b=encodeURIComponent(b);a=(a+"").replace(/[^!#$&-+\--:<-\[\]-~]/g,encodeURIComponent);c=c||{};b=b+"="+a+(c.path?";path="+c.path:"");b+=c.domain?";domain="+c.domain:"";b+=c.expires?";expires="+c.expires.toUTCString():"";return b+=c.secure?";secure":""};a._getCookieObjectFromString=function(b){var d={};b=b?b.split("; "):[];for(var c=0;c<b.length;c++){var f=a._getKeyValuePairFromCookieString(b[c]);d[f.key]===e&&(d[f.key]=f.value)}return d};a._getKeyValuePairFromCookieString=
function(b){var a=b.indexOf("="),a=0>a?b.length:a;return{key:decodeURIComponent(b.substr(0,a)),value:decodeURIComponent(b.substr(a+1))}};a._renewCache=function(){a._cache=a._getCookieObjectFromString(a._document.cookie);a._cachedDocumentCookie=a._document.cookie};a._areEnabled=function(){return a._navigator.cookieEnabled||"1"===a.set("cookies.js",1).get("cookies.js")};a.enabled=a._areEnabled();"function"===typeof define&&define.amd?define(function(){return a}):"undefined"!==typeof exports?("undefined"!==
typeof module&&module.exports&&(exports=module.exports=a),exports.Cookies=a):window.Cookies=a})();

var slider1, slider2, slider3, sliderRV;

(function($) {

	$(function() {

		$('#lang_sel, #lang_sel_list').parent().addClass('wpml-sidebar-widget');

		$('input, textarea').placeholder();

		var navigation2selected = (function() {
		
			"use strict";

			var select = $('<select>');

			function prefix(number) {
				var str = "";
				for(var i = 0; i < number; i++ ) {
					str += "-";
				}
				if(number > 0)
					str += ' ';
				return str;
			}

			function loopNavigation(node, level) {

				var children = node.children(), link;

				if(children.length > 0) {

					children.each(function() {

						link = $(this).children('a');

						select.append(
							$('<option />').text( prefix(level) + link.text()).data('href', link.attr('href') ).attr('selected', link.hasClass('active'))
						);

						loopNavigation($(this).find('ul').eq(0), level+1);
					});
				}
			}

			function init() {

				// Find links and create options for them
				loopNavigation($('nav ul').eq(0), 0);

				// Append newly created select element
				$('#header nav .mobile .styled-select').append(select);

				// Bind an onchange event to redirect
				select.on('change', function() {
					
					window.location.href = $(this).find(':selected').data('href');
				});
			}

			init();
		})();
	});
})(jQuery);

(function($) {

	"use strict";

$(function() {

	$('#header nav .page_item').each(function() {

		if($(this).find('.children').length > 0) {
			$(this).addClass('has-children');
		}
	});

	$('.properties-sort-by').change( function() {
		
		document.location = $(this).val();
	});

	(function() {

		$('.testimonials').each(function() {

			var sliderContainer = $(this).find('ul');

			var isSmartphone = matchMedia('screen and (max-width: 480px)').matches;

			if( sliderContainer.children().length > 1 ) {

				if(!isSmartphone) {
					var slider = sliderContainer.bxSlider({
						nextSelector: $(this).find('.controls .next'),
						prevSelector: $(this).find('.controls .prev'),
						nextText : '',
						prevText : '',
						pager : false
					});
				}

			}

		});

	})();

	try
	{
		$('.tabs').tabs();
		$('.accordion').accordion();
	}
	catch (e) {
		console.log(e.message);
	}
	
	try {
		
		var resizeTimeout = null;

		$(window).resize(function() {
			
			clearTimeout(resizeTimeout);
			resizeTimeout = setTimeout(function() {

				var isSmartphone = matchMedia('screen and (max-width: 480px)').matches;
				var isTablet = matchMedia('screen and (max-width: 979px)').matches;
				var isDesktop = !(isSmartphone && isTablet);

				var slider1options = {},
					sliderRVoptions = {};
				
				// Destory offer slider after resize and recreate it again.
				if(slider1 && slider1.length > 0) {
					slider1.destroySlider();
					slider1 = null;
				}

				if(slider3 && slider3.length > 0) {
					slider3.destroySlider();
					slider3 = null;
				}

				if(isSmartphone || isTablet) {

					$('.slider .slides').css('transform', 'none').find('img').width('100%');
				}

				// Smartphones
				if(isSmartphone) {

					slider1options = {
						minSlides: 1,
						maxSlides: 1,
						slideMargin: 0
					};

					sliderRVoptions = slider1options;

				// Tablets
				} else if(isTablet) {
					
					slider1options = {
						minSlides: 1,
						maxSlides: 2,
						slideMargin: 18,
						slideWidth : $(window).width()/2-2 // 2 x 10 side margins - 6 slide margin
					};

					sliderRVoptions = slider1options;

				// Desktop
				} else {
					
					slider1options = {
						minSlides: 4,
						maxSlides: 4,
						slideWidth : 225,
						slideMargin: 18
					};

					sliderRVoptions = {
						minSlides: 3,
						maxSlides: 3,
						slideWidth : 210,
						slideMargin: 18
					};

				}
				
				// Prevents homepage from blinking
				$('.home .slider .slides').children().addClass('show');
				slider3 = $('.home .slider .slides').bxSlider({
					nextSelector: '.slider .controls .next',
					prevSelector: '.slider .controls .prev',
					nextText : '',
					prevText : '',
					captions: true,
					pager : false,
					responsive : false
				});

				sliderRV = $('.property-viewed .offers.slide .items').bxSlider(sliderRVoptions);

				slider1 = $('.offers.slide .items').not('.property-viewed .offers.slide .items').bxSlider(slider1options);
				
			}, 200);

		}).trigger('resize');
		
	} catch (e) {}

	try {

		// Calling prettyphoto lightbox gallery
		$("a[rel^='prettyPhoto'], .ngg-galleryoverview .ngg-fancybox").prettyPhoto();
	} catch(e) {}

	try {
		$('.estetico-property-gallery').each(function() {

			var gallery = $(this).find('.images');
            var fakeGallery = gallery.clone().insertAfter('.panel');
            var videoSource = '';
            
            //Add property video's info to the fake gallery;
            //Set up video data and source;
            if ($('.video-url').length != 0){
               var href = $('.video-url a').attr('href');
               var src =  $('.video-url a img').attr('src');
               var dataIndex = $('.video-url').data('index');
               fakeGallery.append('<li class="prop-video" data-index="' + dataIndex + '"><a href="' + href + '"><img src="' + src + '"></a></li>');
               
               var videoUrl = $(fakeGallery.find('.prop-video a')).attr("href");
               var videoData = '';
                             
               var patternVimeo = /(?:http?s?:\/\/)?(?:www\.)?(?:vimeo\.com)\/?(.+)/g;
               var patternYoutube = /(?:http?s?:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=)?(.+)/g;
                 
               if(videoUrl.match(patternVimeo)){
                    var vimeoId = videoUrl.match(/https?:\/\/(?:www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/);
                    videoData = "<iframe src='https://player.vimeo.com/video/" + vimeoId[3] + "?api=1&portrait=1' width='650' height='326' frameborder='0'></iframe>";
               }

               if(videoUrl.match(patternYoutube)){
                    var youtubeId = videoUrl.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/);
                    videoData = "<iframe width='650' height='326' src='http://www.youtube.com/embed/" + youtubeId[1] + "?rel=0' frameborder='0' allowfullscreen='false'></iframe>";
               } 
               
               //Json video information source
                var videoSource = {
                    "data" : [
                        {
                            "source" : "http://windowsphonelatest.com/wp-content/uploads/2014/02/quick-controls.jpg",
                            "thumb_source" : "" + $(fakeGallery.find('.prop-video a img')).attr('src') + "",
                            "extra_data" : videoData
                        }
                    ]
                };
            }
            
			gallery.exposure({controlsTarget : '#controls',
				imageControls : true,
				controls : { prevNext : true, pageNumbers : true, firstLast : false },
				pageSize : 6,
				target : $(this).find('.target'),
				slideshowControlsTarget : '#slideshow',
				autostartSlideshow : property_details_gallery_delay > 0,
				slideshowDelay : property_details_gallery_delay * 1000,
                jsonSource : videoSource,
				onThumb : function(thumb) {
					var li = thumb.parents('li');				
					var fadeTo = li.hasClass($.exposure.activeThumbClass) ? 1 : 0.3;
					
					thumb.css({display : 'none', opacity : fadeTo}).stop().fadeIn(200);
					
					thumb.hover(function() { 
						thumb.fadeTo('fast',1); 
					}, function() { 
						li.not('.' + $.exposure.activeThumbClass).children('img').fadeTo('fast', 0.5); 
					});
				},
				onImageHoverOver : function() {
					if (gallery.imageHasData()) {						
						// Show image data as an overlay when image is hovered.
						gallery.dataElement.stop().show().animate({bottom:0+'px'},{queue:false,duration:160});
					}
				},
				onImageHoverOut : function() {
					// Slide down the image data.
					var imageDataBottom = -gallery.dataElement.outerHeight();
					gallery.dataElement.stop().show().animate({bottom:imageDataBottom+'px'},{queue:false,duration:160});
				},
				onImage : function(image, imageData, thumb) {
					var w = gallery.wrapper;
                    
                    // Add data attributes to associate exposure image to its corresponding fakeGallery's hyperlink 
                    // This is to make lightbox work properly
                    gallery.children().each(function(index){
                        index++;
                        $(this).attr('data-index', index);                   
                    });
                    
                    image.attr('data-index', thumb.parent().data('index'));
                    
                    image.click(function(){                       
                        var targetImage = fakeGallery.find("[data-index='" + image.data('index') + "']");
                        targetImage.children().click();
                    });
                    
					// Fade out the previous image.
					image.siblings('.' + $.exposure.lastImageClass).stop().fadeOut(500, function() {
						$(this).remove();
					});
                    
					// Fade in the current image.
					image.hide().stop().fadeIn(1000);
                    
					// Setup hovering for the image data container.
					imageData.hover(function() {
						// Trigger mouse enter event for wrapper element.
						w.trigger('mouseenter');
					}, function() {
						// Trigger mouse leave event for wrapper element.
						w.trigger('mouseleave');
					});
					
					// Check if wrapper is hovered.
					var hovered = w.hasClass($.exposure.imageHoverClass);						
					if (hovered) {
						if (gallery.imageHasData()) {
							gallery.onImageHoverOver();
						} else {
							gallery.onImageHoverOut();
						}	
					}

					if (gallery.showThumbs && thumb && thumb.length) {
						thumb.parents('li').siblings().children('img.' + $.exposure.selectedImageClass).stop().fadeTo(200, 0.5, function() { $(this).removeClass($.exposure.selectedImageClass); });			
						thumb.fadeTo('fast', 1).addClass($.exposure.selectedImageClass);
					}
     
                    //Style property video thumbnail to be the size of the gallery thumbnails
                    if (fakeGallery.find('.prop-video').length != 0
                        && fakeGallery.children().last().data('index') ==  gallery.children().last().data('index')){

                        gallery.children().last().children().css('width','80px');
                    }
				}
			});
            
			$(this).find('.right a').click(function() {
				gallery.nextImage();
			});

			$(this).find('.left a').click(function() {
				gallery.prevImage();
			});
		});
	} catch(e) {
		console.log(e);
	}
	
	/* Safari needs a little boost to display the dropdown controls differently. If user has no JS, the standard Safari controls will be shown. */
	try
	{
		var isSafari = /Constructor/.test(window.HTMLElement);

		if(isSafari) {

			$('html').addClass('safari');
		}

		var isMac = navigator.appVersion.match(/Mac OS/).length > 0;

		if(isMac) {

			$('html').addClass('mac');
		}
	}
	catch (e)
	{
		console.log(e.message);
	}

	$('a[rel="external"]').attr('target', '_blank');

	$('.vision').each(function() {
		$(this).parents('.vc_row-fluid').addClass('component-visions');
	});

	$('.card').each(function() {
		$(this).parents('.vc_row-fluid').addClass('component-cards');
	});

	$('.component-cards, .component-visions').each(function() {
		$(this).addClass('box');
	});

	if($('#contacts-map').length > 0) {

		google.maps.event.addDomListener(window, 'load', function() {

			var lat = $('#contacts-map').data('lat'),
				lng = $('#contacts-map').data('lng');

			var mapOptions = {
				zoom: 12,
				center: new google.maps.LatLng(lat, lng),
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			
			var map = new google.maps.Map(document.getElementById('contacts-map'), mapOptions);

			var latLng = new google.maps.LatLng(lat, lng);
		
			var marker = new google.maps.Marker({
				position: latLng,
				map: map,
				icon : _template_url + '/assets/core/img/marker.png'
			});

		});
	}

	// A little visual fixes to comments form
	$('.comment-form label').each(function() {
		var text = $(this).text();
		$(this).next('input, textarea').attr('placeholder', text);
	});

	$('.comment-form input[type="submit"]').addClass('button th-brown');
});

})(jQuery);

// 10x to Jens (http://stackoverflow.com/questions/3974827/detecting-touch-screen-devices-with-javascript)
function isTouchDevice(){
    return "ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch;
}

/**
 * Add tabs UI to property details page for Google Map and Google Streeet View
 */
jQuery('#property-maps').tabs({
	activate : function(event, ui) {
		if(ui.newPanel.is('#street-view')) {
			if(ui.newPanel.data('street-view-initialized')) {
				return;
			}

			ui.newPanel.data('street-view-initialized', true);
			if(propertiesMap) {
				propertiesMap.addStreetView(propertiesList[0]);
			}
		}
		
	}
});

jQuery('.forced-tabs').tabs({
	activate : function(event, ui) {
		var googleMapsPlaceholder = jQuery(ui.newPanel).find('.properties-map-instance').not('.properties-map-instance-initialized');
		if(googleMapsPlaceholder.length > 0) {
			var propertiesMap = new PropertiesMap(googleMapsPlaceholder.get(0));
			propertiesMap.add(propertiesList);
			propertiesMap.localInitialize();
			googleMapsPlaceholder.addClass('properties-map-instance-initialized');
		}
	}
});

var Estetico = {};

Estetico.RecentlyViewed = (function() {
	this.add = function(id) {

		var key = 'estetico_recently_viewed';

		var options = {
			expires : 60 * 60 * 24,
			path	: '/'
		};

		var value = id;
		var recently_viewed = Cookies.get(key);

		if('undefined' !== typeof recently_viewed && recently_viewed != '') {
			var split = recently_viewed.split(','),
				found = false;

			for(var i = 0, n = split.length; i < n; i++) {
				if(split[i] == id) {
					return;
				}
			}

			split.push(id);
			value = split.join(',');
		}

		Cookies.set(key, value, options);
	}
});