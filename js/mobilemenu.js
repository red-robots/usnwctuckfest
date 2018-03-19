/*
 * This function populates the mobile nav base on the nav in the header and footer 
 */
function populateMobileNav(){
	var $mobileNav = jQuery('nav.mobile');
	var $items=jQuery('header.page nav > ul').children();
	for(var i=0;i<$items.length;i++){
		var $this = jQuery($items[i]);
		var $mobileMenu = jQuery('<ul></ul>').addClass('mobile-menu');
		var $mobileTopLevel = jQuery('<li></li>').addClass('mobile-top-level');
		var $mobileSubMenu = jQuery('<ul></ul>').addClass('mobile-sub-menu');
		$this.children('ul').children('li').children('a').each(function(){
			if(jQuery(this).text()&&jQuery(this).attr('href')){
				$mobileSubMenu.append(jQuery('<a></a>').append(jQuery('<li></li>').text(jQuery(this).text())).attr('href',jQuery(this).attr('href')));
			}
		});
		if($this.children('a').length!==0){
			$mobileTopLevel.text($this.children('a').text());
			if($mobileSubMenu.children().length!==0 ){
				$mobileTopLevel.append($mobileSubMenu);
			}
			$mobileNav.append($mobileMenu.append($mobileTopLevel));
		}
	}
}
/* 
 * This function shows the mobile nav and sets up the event handler for displaying the sub-levels of navigation
 */
function showMobileNav(){
	var $mobileNav = jQuery('nav.mobile');
	var $topLevel=jQuery('nav.mobile .mobile-menu .mobile-top-level');						
	var $subMenu=jQuery('nav.mobile .mobile-menu .mobile-sub-menu');
	$subMenu.each(function(){													//initially hide all sub menus
			jQuery(this).hide();
		});
	$topLevel.show();
	$mobileNav.show();
	$topLevel.on('click',function(){									 
		var $thisSubMenu=jQuery(this).find('.mobile-sub-menu');		//get current sub menu corresponding to current top-level
		$subMenu.not($thisSubMenu).each(function(){								//for each sub menu that isn't current 
			jQuery(this).hide();
		});
		$thisSubMenu.fadeToggle(700);
	});
}
/*
 * This function hides the mobile nav and deregisters the event handlers that act as the menu
 * navigation
 */
function hideMobileNav(){
	jQuery('nav.mobile').hide();
	jQuery('nav.mobile .mobile-menu .mobile-top-level').off('click');
}
/* 
 * This function resets the page-content, showmenu and mobile nav elements to their original states
 * upon a resize of the browser window past ipad formatting at 768px
 */
function resetIfResize(){
	var $pageContainer=jQuery('div.page.container');
	var $header = jQuery('header.page');
	var resetPageContainer=function(){							//function is event handler
		if(jQuery(window).width()>768){
			pushPageLeft();
			$pageContainer.off('click', pageContainerHandler);			//deactivate event listenter for page content once animated to 0 left 0 top
			jQuery('header.page .showmenu').removeClass('active').on('click', showMenuHandler);	//remove class active from hamburger after animation	
			jQuery(window).off('resize',resetPageContainer);			//remove event handler so that it can only be called once
			
		}
	}
	jQuery(window).on('resize',resetPageContainer);
}

/*
 * Animate the page container and header to the right and lock base container so the user can't 
 * scroll
 */
function pushPageRight(){
	var $pageContainer = jQuery('div.page.container');
	var $header = jQuery('header.page');
	var $mobileNav = jQuery('nav.mobile');
	var $window= jQuery(window);
	var windowTop=jQuery(window).scrollTop();		//get the top to set the scroll to later
	var pageHeight=$mobileNav.height()+Number($mobileNav.css('margin-top').replace(/[^0-9\.-]/g,""))+5;
	if(pageHeight<$window.height())pageHeight=$window.height();
	jQuery('.base.container').css({					//lock the base container into position so the user can't scroll
		'width':'100%',								
		'height':pageHeight+"px",			
		'overflow':'hidden'												
	}).scrollTop(windowTop);						//scroll to where the user was, have to do this step last so it scrolls after sized
	$window.scrollTop(0);
	$header.animate({
		left:'250px',
		width:$pageContainer.css('width')					//lock the width so the header doesn't look zoomed as it shifts 
	},300, function(){										//only in some browsers
		$header.css({
			'left':'250px',
			'width':$pageContainer.css('width')
		});
	});
	$pageContainer.animate({								//if page is not pushed over aka not active class on hamburger
		left: '250px'										//animate to 250px
	}, 300, function(){
		$pageContainer.css({
			'left':'250px',
		});
		showMobileNav();
	});
}
/*
 * Animate the page container and header to the left and unlock the base container
 * and hide the mobile nav once complete.
 */
function pushPageLeft(){
	var $pageContainer = jQuery('div.page.container');
	var $header = jQuery('header.page');
	var $window=jQuery(window);
	var windowTop=jQuery('.base.container').scrollTop()+$window.scrollTop();		//get where the user was									
	jQuery('.base.container').css({					//this styling returns the browser to it's original state so that 
		'width':'',								//overflowed items in the dom can still be viewed
		'overflow':'',
		'height':''					
	});
	$window.scrollTop(windowTop);			//scroll the window to where the user was
	hideMobileNav();	
	$header.animate({
		left:'0'
	},300, function(){
		$header.css({
			'left':'0',
			'width':'100%'							//return to original styling
		});											
	});													
	$pageContainer.animate({							//animate back to 0
		left: '0'
	},300, function(){
		$pageContainer.css({
			'left':'0',
		});
	});
}
/* 
 * The following function populates the mobile nav, sets the controls for the horizontal toggle  
 * on mobile and sets the call to view the mobile nav if clicked and reset if the screen is resized. 
 * 
 * It is controlled at first by clicking on the show menu hamburger. This shows the mobile menu and pushes the 
 * section-page-container over.
 * After that the page is returned to its original state via the page container handler once any item on the page is clicked 
 * Note: The on click for the showmenu calls the off function on itself to prevent any event listeners being 
 * redeclared on a showmenu click before the page container has a chance to deregister them. The page container handler
 * resets the showmenu on click.
 */
function showMenuHandler(e){							//event listener for click on hamburger
	var $showMenu = jQuery('header.page .showmenu'); 						//get the hamburger 
	var $pageContainer = jQuery('div.page.container');
	if(!$showMenu.hasClass('active')){						//if not actively animated, if animated do nothing event listener
		resetIfResize();									//on page-content will handle
		e.stopPropagation(); //stop propagation so that page content on click isn't triggered 
		pushPageRight();	
		$showMenu.addClass('active').off('click', showMenuHandler);			//add class active once hamburger is clicked, and remove event listener for show menu so it doesn't redeclare other event listeners
		$pageContainer.on('click', pageContainerHandler);	//set up event listener for return animation on click for section-page-container
	}
}
/*
 * This function acts as the handler for the page container click set in showMenuHandler
 * The function pushes the page back to it's original position, deregisters itself so no further 
 * page alterations can be made from a click without first reseting the handler by clicking
 * the showmenu, and lastly resets the showmenu handler so the functionality is resumed after the page is pushed over
 */
function pageContainerHandler(e){
	e.preventDefault();	//prevent linking			//note any of it's children will bubble up and trigger this
	pushPageLeft();
	jQuery('div.page.container').off('click', pageContainerHandler);						//deactivate event listenter for page content once animated to 0 left 0 top
	jQuery('header.page .showmenu').removeClass('active').on('click', showMenuHandler);				//remove class active from hamburger after animation
}

/* 
 * This section of the code starts the ball rolling and calls the function to populate the mobile nav. 
 * It also sets the event handler for the show menu after the document DOM is ready.
 */
jQuery(document).ready(function(){
	populateMobileNav();												//have to populate the nav before getting the items
	jQuery('header.page .showmenu').on('click', showMenuHandler);
});