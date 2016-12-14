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
function Scheduler(){
	return {
		ltPtr: 0,
		gtPtr: 0,
		fnGtPtr: 0,
		fnLtPtr: 0,
		scheduleAll: new Array(),
		scheduleLT: new Array(),
		scheduleGT: new Array(),
		addAll: function(obj){
			this.scheduleAll.push(obj);
		},
		addLT: function(obj){
			this.scheduleLT.push(obj);
			this.fnLtPtr=this.scheduleLT.length-1;
		},
		addGT: function(obj){
			this.scheduleGT.push(obj);
			this.fnGtPtr=this.scheduleGT.length-1;
		},
		reset: function(){
			this.ltPtr= 0;
			this.gtPtr= 0;
			this.fnGtPtr= 0;
			this.fnLtPtr= 0;
			this.scheduleAll= new Array();
			this.scheduleLT= new Array();
			this.scheduleGT= new Array();
		},
		initAll: function(){
			for(var i=0;i<this.scheduleAll.length;i++){
				jQuery(this.scheduleAll[i].target).on(this.scheduleAll[i].event,"",{index: i, objThis: this}, this.executeAll);
			}	
		},
		executeAll: function(func, item_in){
			var index, objThis, item;
			if(item_in)item=item_in;
			if(typeof func==="object"){
				index=func.data.index;
				item=jQuery(this);
				objThis=func.data.objThis;
				jQuery(objThis.scheduleAll[index].target).off(objThis.scheduleAll[index].event,objThis.executeAll);
			}
			else {
				objThis=this;
				index=func;
				if(!item_in)item=jQuery(this);
			}
			if(objThis.scheduleAll[index].ptr<objThis.scheduleAll[index].funcs.length){
				objThis.scheduleAll[index].ptr++;
				if(objThis.scheduleAll[index].pass){
					objThis.scheduleAll[index].funcs[objThis.scheduleAll[index].ptr-1](item,index);
				}
				else objThis.scheduleAll[index].funcs[objThis.scheduleAll[index].ptr-1](index);
			} else {
				objThis.scheduleAll[index].ptr=0;
				jQuery(objThis.scheduleAll[index].target).on(objThis.scheduleAll[index].event,"",{index: index, objThis: objThis}, objThis.executeAll);
			}
		},
		executeGT: function(){
			if(this.gtPtr<this.scheduleGT.length){
				this.gtPtr++;			
				this.scheduleGT[this.gtPtr-1].execute();
			} else {
				this.gtPtr=0;
			}
		},
		finishGT: function(){
			if(this.fnGtPtr>-1){
				this.fnGtPtr--;
				this.scheduleGT[this.fnGtPtr+1].finish();
			} else {
				this.fnGtPtr=this.scheduleGT.length-1;
				this.executeLT();
			}
		},
		executeLT: function(){
			if(this.ltPtr<this.scheduleLT.length){
				this.ltPtr++;
				this.scheduleLT[this.ltPtr-1].execute();
			} else {
				this.ltPtr=0;
			}
		},
		finishLT: function(){
			if(this.fnLtPtr>-1){
				this.fnLtPtr--;
				this.scheduleLT[this.fnLtPtr+1].finish();

			} else {
				this.fnLtPtr=this.scheduleLT.length-1;
				this.executeGT();
			}
		}
	}
}
jQuery(window).load(function(){
	var s= new Scheduler();
	var so = new Sort(s), touch= new Touchable(s), rot= new Rotator(s), sw = new Swipe(s);
	var $window= jQuery(window);
	s.addLT(sw);
	s.addLT(rot);
	s.addLT(touch);
	s.addGT(so);
	s.addAll({
		target:'.touchable.tile.container .tile.touchable', 
		event:'click',
		funcs: new Array(
			touch.click,
			so.click
		),
		ptr: 0,
		pass: true
	});
	s.initAll();
	if($window.width()>670){
		s.executeGT();
		$window.on('resize', boundaryCrossLeft);
	}
	else {
		s.executeLT();
		$window.on('resize', boundaryCrossRight);
	}
	function boundaryCrossRight(){
		if($window.width()>670){
			s.finishLT();
			$window.off('resize', boundaryCrossRight);
			$window.on('resize', boundaryCrossLeft);
		}
	}
	function boundaryCrossLeft(){
		if($window.width()<=670){
			s.finishGT();
			$window.on('resize', boundaryCrossRight);
			$window.off('resize', boundaryCrossLeft);
		}
	}
});