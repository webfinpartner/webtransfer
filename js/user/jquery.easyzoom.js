/**
 * jQuery plugin - Easy Zoom (modified)
 * 
 * @author Alen Grakalic
 * @author Matt Hinchliffe
 * @author Marvin Elia Hoppe
 * @license Creative Commons Attribution-ShareAlike 3.0
 * @version 1.0.7
 */
;(function ($) {

	/**
	 * Define plugin name
	 */
	var pluginName = "easyZoom";
	
	/**
	 * Define default properties
	 */
	var defaults = {
			interval: {
				notification: 1500,
				animation: 300
			},
			image: {
				lowResolution: null,
				properties: {
					relation: {
						height: null,
						width: null							
					}						
				}					
			},
			notifications: {				
				error: "There has been a problem with loading the image!",
				loading: "Loading high resolution image..."					
			},			
			resource: {
				image: new Image(),
				isLoaded: false,					
				reference: null					
			},
			selector: {
				preview: "#preview-zoom",
				window: "#window-zoom"
			}
	};

	/**
	 * Plugin constructor
	 */
	function Plugin(element, options) {		
		/**
		 * Select low resolution image
		 */
		defaults.image.lowResolution = $("img:first", element);
		
		this.element = element;
		this.settings = $.extend(defaults, options);
		this._defaults = defaults;
		this._name = pluginName;
		this.init();
	};

	/**
	 * Plugin prototype
	 */
	Plugin.prototype = {
			/**
			 * Initialize the plugin
			 */
			init: function (referenceOfImage) {				
				this.settings.resource.reference = (typeof referenceOfImage != "undefined") ? referenceOfImage : $(this.element).attr("data-image");
				this.reset().attachEventListener();
			},
			/**
			 * Adjust the high resolution image inside the zoom window
			 * 
			 * @param positionLeft
			 * @param positionTop
			 * @returns {Plugin}
			 */
			adjustHighResolutionImage: function(positionLeft, positionTop) {
				$(this.settings.selector.window).children("img:first").css({left: positionLeft, top: positionTop});
				return this;				
			},
			/**
			 * Attach serveral eventlistener to the given element
			 * 
			 * @returns {Plugin}
			 */
			attachEventListener: function() {
				var plugin = this;
				
				$(plugin.element).on({
					mousemove: function(event) {
						plugin.recognizeMouseMovement(event);						
					},
					mouseover: function() {
						plugin.start();
					},
					mouseout: function(event) {
						if(!plugin.previewRemainsInScope(event)){
							plugin.fadeOut();
						}						
					}
				});
				return this;		
			},
			/**
			 * Calculate the relation of the geometry between the given images
			 * 
			 * @param imageHighResolution
			 */
			calculateRelationOfImageGeometry: function(imageHighResolution) {				
				this.settings.image.properties.relation.width =
					imageHighResolution.width / this.settings.image.lowResolution.width();
				
				this.settings.image.properties.relation.height =
					imageHighResolution.height / this.settings.image.lowResolution.height();				
			},
			/**
			 * Defer fadeout by the given time in milliseconds
			 * 
			 * @param timeInMilliseconds
			 */
			deferredFadeOut: function(timeInMilliseconds) {
				var plugin = this;
				setTimeout(function() {plugin.fadeOut();}, timeInMilliseconds);
			},
			/**
			 * Detach serveral event listener otherwise events will be called multiple times
			 * 
			 * @returns {Plugin}
			 */
			detachEventListener: function() {
				$(this.element).off();
				return this;
			},
			/**
			 * Fadeout various elements which have been made visible before
			 * 
			 * @returns {Plugin}
			 */
			fadeOut: function() {
				$(this.settings.selector.preview).fadeOut(this.settings.interval.animation);
				$(this.settings.selector.window).fadeOut(this.settings.interval.animation);
				return this;
			},
			/**
			 * Retrieve a list of position properties used to adjust the preview
			 * 
			 * @param event
			 * @returns {multitype:integer}
			 */
			getPropertiesOfPreview: function(event) {
				
				var properties = {
						height: $(this.settings.selector.window).height() / this.settings.image.properties.relation.height,
						width: $(this.settings.selector.window).width() / this.settings.image.properties.relation.width
				};
				
				var offsetOfParentElement = $(this.settings.image.lowResolution).offsetParent().offset();
				
				return $.extend(properties, {
					left: event.pageX - (properties.width / 2) - offsetOfParentElement.left,	
					top: event.pageY - (properties.height / 2) - offsetOfParentElement.top
				});			
			},			
			/**
			 * Modify the cursor appearance when a hover event of the element will be triggered
			 * 
			 * @param appearance
			 * @returns {Plugin}
			 */
			modifyCursorAppearance: function(appearance) {
				$(this.element).css("cursor", appearance);
				return this;		
			},
			/**
			 * Review wether the preview remains in the scope of the given element
			 * 
			 * @param event
			 * @returns {Boolean}
			 */
			previewRemainsInScope: function(event) {				
				var basis = $(this.settings.image.lowResolution);
				
				if(event.pageX < basis.offset().left){
					return false;
				}
				if(event.pageX > basis.offset().left + basis.width()){
					return false;				
				}
				if(event.pageY < basis.offset().top){
					return false;
				}
				if(event.pageY > basis.offset().top + basis.height()){
					return false;				
				}				
				return true;				
			},
			/**
			 * Recognize mouse movement, update relating elements
			 * 
			 * @param event
			 */
			recognizeMouseMovement: function(event) {
				if(this.settings.resource.isLoaded){
					if(this.previewRemainsInScope(event)){	
						
						var positionLeft = 
							((event.pageX - this.settings.image.lowResolution.offset().left) * this.settings.image.properties.relation.width) 
							- ($(this.settings.selector.window).width() / 2);
						
						var positionTop = 
							((event.pageY - this.settings.image.lowResolution.offset().top) * this.settings.image.properties.relation.height) 
							- ($(this.settings.selector.window).height() / 2);

						this.adjustHighResolutionImage(-positionLeft, -positionTop).showZoomWindow().showPreview(this.getPropertiesOfPreview(event));		
					}
					else{
						this.fadeOut();
					}					
				}	
			},
			/**
			 * Reset this jQuery plugin instance
			 * 
			 * @returns {Plugin}
			 */
			reset: function() {
				this.settings.resource.isLoaded = false;			
				return this.fadeOut().detachEventListener();
			},
			/**
			 * Shows an error notification, after a shot time the zoom window will be hidden
			 */
			showErrorNotification: function() {
				this.modifyCursorAppearance("auto").showNotification(this.settings.notifications.error).deferredFadeOut(this.settings.interval.notification);
			},
			/**
			 * Shows the given notification inside the zoom window
			 * 
			 * @param notification
			 * @returns {Plugin}
			 */
			showNotification: function(notification) {
				$(this.settings.selector.window).text(notification).fadeIn(this.settings.interval.animation);
				return this;
			},
			/**
			 * Show the preview of the magnified area
			 * 
			 * @param properties
			 */
			showPreview: function(properties) {
				$(this.settings.selector.preview).css(properties).fadeIn(this.settings.interval.animation);
			},
			/**
			 * Show the zoom window with the magnified image inside it
			 * 
			 * @returns {Plugin}
			 */			
			showZoomWindow: function() {
				$(this.settings.selector.window).html(this.settings.resource.image).fadeIn(this.settings.interval.animation);
				return this;
			},
			/**
			 * Start this jQuery plugin instance
			 */
			start: function() {
				this.modifyCursorAppearance("auto");
				
				if(!this.settings.resource.isLoaded){
					this.modifyCursorAppearance("progress");
					this.showNotification(this.settings.notifications.loading);				
					this.waitUntilResourceIsLoaded(this.settings.resource.reference);				
				}	
				else{
					/**
					 * Show initial zoom window
					 */
					this.showZoomWindow();
				}
			},
			/**
			 * Wait until the resource is loaded
			 * 
			 * @param reference
			 * @returns {Plugin}
			 */
			waitUntilResourceIsLoaded: function(reference) {
				/**
				 * Local plugin wrapping otherwise "this" will reference the loaded image
				 */
				var plugin = this;
				
				this.settings.resource.image.src = reference;
				
				$(this.settings.resource.image).load(function() {
					plugin.settings.resource.isLoaded = true;				
					plugin.calculateRelationOfImageGeometry(this);

					/**
					 * Restart this jQuery plugin instance
					 */
					plugin.start();				
					}
				).error(function() {
					plugin.showErrorNotification();
					}
				);					
				return plugin;
			}
	};

	/**
	 * jQuery plugin wrapper
	 */
	$.fn[pluginName] = function(options) {		
		return this.each(function() {
			$.data(this, pluginName, new Plugin(this, options));			
		});
	};

})(jQuery);