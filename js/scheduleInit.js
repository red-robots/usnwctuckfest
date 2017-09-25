(function($){
	$.fn.totalWidth=function(){
		//get all width data for the current tile/icon
		var thisWidth=Number(this.css('width').replace(/[^0-9\.-]/g,""));					
		var thisMarginRight=Number(this.css('margin-right').replace(/[^0-9\.-]/g,""));
		var thisMarginLeft=Number(this.css('margin-left').replace(/[^0-9\.-]/g,""));
		var thisPaddingLeft=Number(this.css('padding-left').replace(/[^0-9\.-]/g,""));
		var thisPaddingRight=Number(this.css('padding-right').replace(/[^0-9\.-]/g,""));
		var thisBorderLeft=Number(this.css('border-left-width').replace(/[^0-9\.-]/g,""));
		var thisBorderRight=Number(this.css('border-right-width').replace(/[^0-9\.-]/g,""));
		//return sum
		return thisWidth+thisMarginLeft+thisMarginRight+thisPaddingLeft+
			thisPaddingRight+thisBorderLeft+thisBorderRight;
	};
	$.fn.totalHeight=function(){
		//get all height data for the current tile/icon
		var thisHeight=Number(this.css('height').replace(/[^0-9\.-]/g,""));
		var thisMarginTop=Number(this.css('margin-bottom').replace(/[^0-9\.-]/g,""));
		var thisMarginBottom=Number(this.css('margin-bottom').replace(/[^0-9\.-]/g,""));
		var thisPaddingTop=Number(this.css('padding-top').replace(/[^0-9\.-]/g,""));
		var thisPaddingBottom=Number(this.css('padding-bottom').replace(/[^0-9\.-]/g,""));
		var thisBorderTop=Number(this.css('border-top-width').replace(/[^0-9\.-]/g,""));
		var thisBorderBottom=Number(this.css('border-bottom-width').replace(/[^0-9\.-]/g,""));
		//return sum
		return thisHeight+thisMarginTop+thisMarginBottom+thisPaddingTop+thisPaddingBottom+
			thisBorderTop+thisBorderBottom;
	};
})(jQuery);
jQuery(window).load(function(){
	var s= new Scheduler();
	var so = new Sort(s, {
		container: jQuery('.tile.container')
	}), 
	touch= new Touchable(s, {
		container: jQuery('.touchable.tile.container')
	}), 
	rot= new Rotator(s, {
		container: jQuery('.rotator.container'),
		slidestoshow: 3,
		slidewidth: 74,
		slidemargin: 10,
		delay: 0,
		scrollSpeed: 4000
	}), 
	sw = new Swipe(s, {
		container: document.getElementsByClassName('slideable container')
	});
	var $window= jQuery(window);
	//s.addLT(sw);
	s.addLT(rot);
	//s.addGT(so);
	/*s.addAll({
		target:'.touchable.tile.container .tile.touchable > img, .touchable.tile.container .tile.touchable > header',
		event:'click',
		funcs: new Array(
			touch.click,
			so.click
		),
		ptr: 0,
		pass: true
	});*/
	s.initAll();
	if((window.innerWidth?window.innerWidth:document.documentElement.clientWidth)>670){
		s.executeGT();
		$window.on('resize', boundaryCrossLeft);
	}
	else {
		s.executeLT();
		$window.on('resize', boundaryCrossRight);
	}
	function boundaryCrossRight(){
		if((window.innerWidth?window.innerWidth:document.documentElement.clientWidth)>670){
			s.finishLT();
			$window.off('resize', boundaryCrossRight);
			$window.on('resize', boundaryCrossLeft);
		}
	}
	function boundaryCrossLeft(){
		if((window.innerWidth?window.innerWidth:document.documentElement.clientWidth)<=670){
			s.finishGT();
			$window.on('resize', boundaryCrossRight);
			$window.off('resize', boundaryCrossLeft);
		}
	}
});