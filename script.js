var page_min_width = 940;
var mobile_width = 500;
var isotope_set = false;
var is_filtering = false;
var current_page_width = 0;

jQuery.noConflict();

// QuickEach
jQuery.fn.quickEach = (function(){
    var jq = jQuery([1]);
    return function(c) {
        var i = -1, el, len = this.length;
        try {
            while (
                 ++i < len &&
                 (el = jq[0] = this[i]) &&
                 c.call(jq, i, el) !== false
            );
        } catch(e){
            delete jq[0];
            throw e;
        }
        delete jq[0];
        return this;
    };
}());

(function($) {
	
	// Responsive Videos
	$('.vid-container').fitVids();
	
	// Show/Hide Filter Menu
	//   uncomment to show and hide the filter nav
	/*$('#filtering-nav ul').hide();
	$('a.filter-btn').click(function(){
		$('#filtering-nav ul').slideToggle();
		return false;
	});*/
	
	
	
	// Submenus
	var submenu_config = {    
		 over: function(){ $('ul', this).fadeIn(200); },  
		 timeout: 300,
		 out: function(){ $('ul', this).fadeOut(300); }  
	};
	$('.header-nav ul > li').not(".header-nav ul li li").hoverIntent(submenu_config);
	
	// Create the dropdown base
	$("<select />").appendTo(".header-nav");
	
	// Create default option "Go to..."
	$("<option />", {
	   "selected": "selected",
	   "value"   : "",
	   "text"    : "Go to..."
	}).appendTo(".header-nav select");
	
	// Populate dropdown with menu items
	$(".nav a").each(function() {
	 	var el = $(this);
	 	
	 	if( el.parent().parent().hasClass('sub-menu') ){
	 		var prefix = "- ";
	 	} else{
	 		var prefix = '';
	 	}
	 	
		$("<option />", {
		     "value"   : el.attr("href"),
		     "text"    : prefix + el.text()
		}).appendTo(".header-nav select");
	});

	// Dropdown menu clicks
	$(".header-nav select").change(function() {
        window.location = $(this).find("option:selected").val();
	});
	
	// Vertically align header items
	$(window).load(function(){
	   $('#site-info, #social-networks, .header-nav ul:not(.sub-menu)').vit();
	});
	
	// Sidebar Ads
	$('.shaken_sidebar_ads a:odd img').addClass('last-ad');
	
	// Share Icons
	$('.share-container').hide();
	
	$('.share').on('click', function(){
		$('.share-container', $(this).parent()).slideToggle('fast');				   
	});
	
	// Display pop-up when clicking on share icons
	$('.share-window').on('click', function(){
		var width  = 650;
		var height = 500;
		var left   = (screen.width  - width)/2;
		var top    = (screen.height - height)/2;
		var params = 'width='+width+', height='+height;
		params += ', top='+top+', left='+left;
		params += ', directories=no';
		params += ', location=no';
		params += ', menubar=no';
		params += ', resizable=no';
		params += ', scrollbars=no';
		params += ', status=no';
		params += ', toolbar=no';
		newwin=window.open($(this).attr('href'),'Share', params);
		if (window.focus) {newwin.focus();}
		return false;
	});
	
	// Lightbox Init
	var fancyboxArgs = {
	    padding: 0,
	    overlayColor: "#000",
	    overlayOpacity: 0.85,
	    titleShow: false
	};
	$('.gallery-icon a').attr('rel', 'post-gallery');
	$("a[rel='gallery'], a[rel='lightbox'], .gallery-icon a, .colorbox").fancybox( fancyboxArgs );
	
	// Remove margins
	$('.gallery-thumb:nth-child(3n)').addClass('last');
	
	// Slider Init
	$('.slider').slides({
		play: 9500,
		pause: 2500,
		hoverPause: true,
		effect: 'fade',
		generatePagination: false
	});
	
	$(window).load(function(){
		
		// Vertically center all images in the slider
		$('.slides_container').quickEach(function(){
			var containerH = this.height();
			
			$('img', this).quickEach(function(){
				var imgH = this.height();	
				if(imgH != containerH){
					var margin = (containerH - imgH)/2; 
					this.css('margin-top', margin);
				}
			});
		});
		
		setIsotope();
		
		// Filtering
		$('#filtering-nav li a').click(function(){
            is_filtering = true;
			var selector = $(this).attr('data-filter');
		  	$('#sort, .sort').isotope({ filter: selector });
		  	is_filtering = false;
		  	return false;
		});
		
		// Sticky footer
		stickyFooter();
	});
	
	$(window).resize(function(){
        stickyFooter();
        setIsotope();
	});
})(jQuery);

// Setup grid structure for blog layout 
function setIsotope(){
	if( jQuery(window).width() > mobile_width && !isotope_set){
        
        isotope_set = true;
        
		// Isotope Init		
		jQuery('.sort, #sort').isotope({
			itemSelector : '.box:not(.invis)',
			transformsEnabled: false,
			masonry: {
				//columnWidth : 175 // original
				columnWidth : 110   // for iPad
			},
			onLayout: function(){
                centerLayout();
			}
		});
		
    }
}



function stickyFooter(){
    jQuery('#footer').css('position', 'relative');
    
    if( jQuery('body').height() < jQuery(window).height() ){
        jQuery('#footer').css({
            'position': 'fixed',
            'bottom': 0,
            'width': '100%'
        });
    }
}

/*  Centered Masonry Layout
 **************************************** */
  jQuery.Isotope.prototype._getCenteredMasonryColumns = function() {
    this.width = this.element.width();
    
    if( this.element.parent().hasClass('timeline')){
        var parentWidth = page_min_width;
    } else {
        var parentWidth = this.element.parent().width();
    }
    
                  // i.e. options.masonry && options.masonry.columnWidth
    var colW = this.options.masonry && this.options.masonry.columnWidth ||
                  // or use the size of the first item
                  this.$filteredAtoms.outerWidth(true) ||
                  // if there's no items, use size of container
                  parentWidth;
    
    var cols = Math.floor( parentWidth / colW );
    cols = Math.max( cols, 1 );

    // i.e. this.masonry.cols = ....
    this.masonry.cols = cols;
    // i.e. this.masonry.columnWidth = ...
    this.masonry.columnWidth = colW;
  };
  
  jQuery.Isotope.prototype._masonryReset = function() {
    // layout-specific props
    this.masonry = {};
    // FIXME shouldn't have to call this again
    this._getCenteredMasonryColumns();
    var i = this.masonry.cols;
    this.masonry.colYs = [];
    while (i--) {
      this.masonry.colYs.push( 0 );
    }
  };

  jQuery.Isotope.prototype._masonryResizeChanged = function() {
    var prevColCount = this.masonry.cols;
    // get updated colCount
    this._getCenteredMasonryColumns();
    return ( this.masonry.cols !== prevColCount );
  };
  
  jQuery.Isotope.prototype._masonryGetContainerSize = function() {
    var unusedCols = 0,
        i = this.masonry.cols;
    // count unused columns
    while ( --i ) {
      if ( this.masonry.colYs[i] !== 0 ) {
        break;
      }
      unusedCols++;
    }
    
    if( !is_filtering ){
        var returnWidth = (this.masonry.cols - unusedCols) * this.masonry.columnWidth;
    
        if(returnWidth < page_min_width && jQuery(window).width() > 768){
            returnWidth = page_min_width
        }
        
        current_page_width = returnWidth;
    } else {
        returnWidth = current_page_width;
    }
    
    
    
    return {
          height : Math.max.apply( Math, this.masonry.colYs ),
          // fit container to columns that have been used;
          width : returnWidth
    };
  };
  

/* Deleting this all for FUCKING IE!!! */
/*function centerLayout(){
    if( jQuery(window).width() > mobile_width ){
        jQuery('#header .wrap, #breadcrumbs .wrap, #footer .wrap, #filtering-nav, .navigation').css('width', jQuery('.isotope').width() - 10);
    }
}*/