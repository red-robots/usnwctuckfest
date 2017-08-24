function Touchable(scheduler, $args_obj){
	var $window = jQuery(window);
	return {
		execute: function(){
			scheduler.executeLT();
		},
		finish: function(){
			scheduler.finishLT();
		},
		click: function($target,index){
			var $tile=$target.parents('.tile.touchable').eq(0);
			$tile.children('section.copy').toggle(0, function(){
				if((window.innerWidth?window.innerWidth:document.documentElement.clientWidth)<=670){
					$target.parents('.touchable.tile.container').eq(0).height($tile.totalHeight());
				}
				$window.scrollTop($tile[0].getBoundingClientRect().top+$window.scrollTop());
				scheduler.executeAll(index);
			});
		}
	}
}
function Swipe(scheduler, args_obj){
	var t, prevInnerWidth=window.innerWidth, rotatingFlag;
	function initSwipe(){
		var containers=args_obj.container;
		rotatingFlag=false;
		for(var k=0;k<containers.length;k++){
			var els=containers[k].getElementsByClassName('slideable');
			var activeTileHeight=0;
			var activeTileIndex=0;
			var contStyle=window.getComputedStyle(containers[k],null);
			var tileHeightNoCopy=0;
			for(var i=0;i<els.length;i++){
				if(els[i].className.match(/\bactive\b/gi)){
					activeTileIndex=i;
					break;
				}
			}	
			for(var i=0;i<els.length;i++){
				var el=els[i];
				if(i===activeTileIndex){
					if(el.getElementsByClassName("copy")[0]){
						var tileCopyStyle=window.getComputedStyle(el.getElementsByClassName("copy")[0],null);
						if(tileCopyStyle.display!=="none"){
							tileHeightNoCopy=totalHeight(el)-Number(tileCopyStyle.height.replace(/[^0-9\.-]/g,""));
						}
						else tileHeightNoCopy=totalHeight(el);
					}
					else tileHeightNoCopy=totalHeight(el);
					el.style.left=Number(contStyle.width.replace(/[^0-9\.-]/g,""))/2
						-totalWidth(el)/2+"px";
					el.style.display="block";
					el.className=el.className.replace(/\bactive\b\s*/gi,"").replace(/\b\s*$/gi,"")+" active";
					activeTileHeight=totalHeight(el);
				}
				else {
					el.style.display="none";
					el.className=el.className.replace(/\bactive\b\s*/gi,"").replace(/\b\s*$/gi,"");
				}
				el.style.position="absolute";
				el.style.top=0+"px";
				if(window.TouchEvent)scope(el);
			}
			containers[k].style.height=activeTileHeight+"px";
			var rightArrow=document.createElement("img");
			var leftArrow=document.createElement("img");
			var arrowLoadNum=0;
			rightArrow.src="/wp-content/uploads/2017/08/RightArrow.png";
			rightArrow.className="right arrow";
			rightArrow.style.position="absolute";			
			leftArrow.src="/wp-content/uploads/2017/08/LeftArrow.png";
			leftArrow.className="left arrow";
			leftArrow.style.position="absolute";
			containers[k].appendChild(leftArrow);
			containers[k].appendChild(rightArrow);
			function setArrows(){
				arrowLoadNum++;
				if(arrowLoadNum===2){
					leftArrow.style.top= tileHeightNoCopy/2-totalHeight(leftArrow)/2+"px";
					leftArrow.style.left= "0px";
					rightArrow.style.top= tileHeightNoCopy/2-totalHeight(rightArrow)/2+"px";
					rightArrow.style.left= Number(contStyle.width.replace(/[^0-9\.-]/g,""))-totalWidth(rightArrow)+"px";
					leftArrow.addEventListener("click", rotateRight, false);
					rightArrow.addEventListener("click", rotateLeft, false);
				}
			}
			rightArrow.addEventListener("load", setArrows, false);
			leftArrow.addEventListener("load", setArrows, false);
		}
	}
	function resetSwipe(){
		var containers=args_obj.container;
		for(var k=0;k<containers.length;k++){
			var els=containers[k].getElementsByClassName('slideable');
			containers[k].style.height="";
			for(var i=0;i<els.length;i++){
				var el=els[i];
				if(window.TouchEvent)scope(el);
				el.style.left="";
				el.style.top="";
				el.style.display="";
				el.style.position="";
			}
			var leftArrows=containers[k].getElementsByClassName("left arrow");
			for(var i=leftArrows.length-1;i>-1;i--){
				leftArrows[i].removeEventListener("click",rotateLeft);
				containers[k].removeChild(leftArrows[i]);
			}
			var rightArrows=containers[k].getElementsByClassName("right arrow");
			for(var i=rightArrows.length-1;i>-1;i--){
				rightArrows[i].removeEventListener("click",rotateRight);
				containers[k].removeChild(rightArrows[i]);
			}
		}
	}
	function centerEl(){
		if(window.innerWidth!==prevInnerWidth){
			prevInnerWidth=window.innerWidth;
			clearTimeout(t);
			if((window.innerWidth?window.innerWidth:document.documentElement.clientWidth)<=670&&(window.innerWidth?window.innerWidth:document.documentElement.clientWidth)>=320){
				t=setTimeout(function(){
					if((window.innerWidth?window.innerWidth:document.documentElement.clientWidth)<=670&&(window.innerWidth?window.innerWidth:document.documentElement.clientWidth)>=320){
						var $containers=jQuery(args_obj.container);
						$containers.each(function(){
							var $singleContainer=jQuery(this);
							var $el=$singleContainer.find('.slideable.active').eq(0);
							$el.css({
								'left': ($singleContainer.width()/2)-($el.totalWidth()/2)+"px"
							});
							var $arrowLeft=$singleContainer.children('.left.arrow');
							var $arrowRight=$singleContainer.children('.right.arrow');
							var $elCopy=$el.find('section.copy');
							var tileHeightNoCopy;
							if($elCopy){
								if($elCopy.css('display')!=="none")tileHeightNoCopy=$el.height()-$elCopy.height();
								else tileHeightNoCopy=$el.height();
							}
							else tileHeightNoCopy=$el.height();
							if($arrowLeft.length>0){
								$arrowLeft.css({
									'left': "0px",
									'top': tileHeightNoCopy/2-$arrowLeft.totalHeight()/2+"px"
								});
							}
							if($arrowRight.length>0){
								$arrowRight.css({
									'left': $singleContainer.width()-$arrowRight.totalWidth()+"px",
									'top': tileHeightNoCopy/2-$arrowRight.totalHeight()/2+"px"
								});
							}
						});
					}
				}, 200);
			}
		}
	}
	function scope(el){
		if((window.innerWidth?window.innerWidth:document.documentElement.clientWidth)>670){
			el.removeEventListener('touchstart', swipeStart, false);
			el.removeEventListener('touchmove', swipeHandler, false);
			el.removeEventListener('touchend', swipeEnd, false);
		}
		else {
			var downY, downX, startX, startY, elStyle=window.getComputedStyle(el,null);
			el.addEventListener('touchstart', swipeStart, false);
			el.addEventListener('touchmove', swipeHandler, false);
			el.addEventListener('touchend', swipeEnd, false);
		}		
		function swipeStart(e){
			downY=e.targetTouches[0].clientY;
			downX=e.targetTouches[0].clientX;
			startX=Number(elStyle.left.replace(/[^0-9\.-]/g,""));
			startY=Number(elStyle.top.replace(/[^0-9\.-]/g,""));
		}
		function swipeHandler(e){
			if(abs(e.targetTouches[0].clientY-downY)<abs(e.targetTouches[0].clientX-downX)){
				e.preventDefault();
				el.style.left=startX+(e.targetTouches[0].clientX-downX)*0.25+"px";
			}
		}
		function swipeEnd(e){
			for(var j=0;j<e.changedTouches.length;j++){
				if(isDescendant(el,e.changedTouches[j].target)){
					if(e.changedTouches[j].clientX-downX>50){
						rotateRight(el);
					}
					else if(downX-e.changedTouches[j].clientX>50){
						rotateLeft(el);
					}
					else {
						jQuery(el).animate({
							left: startX+"px"
						}, 300);
					}
				}
			}
		}
	}
	function rotateRight(e){
		if(!rotatingFlag){
			var $prevEl;
			rotatingFlag=true;
			if(e.target){
				var $container=jQuery(e.target).parents('.slideable.container').eq(0);
				var $els=$container.find('.slideable');
				var $el=$els.filter('.active').eq(0);
				var elIndex=$els.index($el);
			}
			else {
				var $el = jQuery(e);
				var $container = $el.parents('.slideable.container').eq(0);
				var $els=$container.find('.slideable');
				var elIndex=$els.index($el);
			}
			if(elIndex-1>-1){
				$prevEl= $els.eq(elIndex-1);
			}
			else {
				$prevEl=$els.eq($els.length-1);
			}
			if($prevEl.length!==0){
				var ww=jQuery(window).width();
				$prevEl.css({
					'left':$container.width()/2-$prevEl.totalWidth()/2-ww+"px",
					'display':'block'
				}).show().animate({
					left: Number($prevEl.css('left').replace(/[^0-9\.-]/g,""))+ww+"px",
				}, 300, "swing").addClass('active');
				$el.animate({
					left: Number($el.css('left').replace(/[^0-9\.-]/g,""))+ww+"px",
				}, 300, "swing", function(){
					$el.hide();
					$container.height($prevEl.totalHeight());
					rotatingFlag=false;
				}).removeClass('active');
			}
			else animateCenter();
			function animateCenter(){
				var center=$container.width()/2-$el.totalWidth()/2;
				if(Number($el.css('left').replace(/[^0-9\.-]/g,""))===center){
					$el.animate({
						left: center+20+"px"
					}, 150, function(){
						$el.animate({
							left: center+"px"
						}, 150, function(){rotatingFlag=false;});
					});
				}
				else {
					$el.animate({
						left: center+"px"
					}, 300, function(){rotatingFlag=false;});
				}
			}
		}
	}
	function rotateLeft(e){	
		if(!rotatingFlag){
			var $nextEl;
			rotatingFlag=true;
			if(e.target){
				var $container=jQuery(e.target).parents('.slideable.container').eq(0);
				var $els=$container.find('.slideable');
				var $el=$els.filter('.active').eq(0);
				var elIndex=$els.index($el);
			}
			else {
				var $el = jQuery(e);
				var $container = $el.parents('.slideable.container').eq(0);
				var $els=$container.find('.slideable');
				var elIndex=$els.index($el);
			}
			if(elIndex+1<$els.length){
				$nextEl= $els.eq(elIndex+1);
			}
			else {
				$nextEl=$els.eq(0);
			}
			if($nextEl.length!==0){
				var ww=jQuery(window).width();
				$nextEl.css({
					'left':$container.width()/2-$nextEl.totalWidth()/2+ww+"px"
				}).show().animate({
					left: Number($nextEl.css('left').replace(/[^0-9\.-]/g,""))-ww+"px",
				}, 300, "swing").addClass('active');
				$el.animate({
					left: Number($el.css('left').replace(/[^0-9\.-]/g,""))-ww+"px",
				}, 300, "swing", function(){
					$el.hide();
					$container.height($nextEl.totalHeight());
					rotatingFlag=false;
				}).removeClass('active');
			}
			else animateCenter();
			function animateCenter(){
				var center=$container.width()/2-$el.totalWidth()/2;
				if(Number($el.css('left').replace(/[^0-9\.-]/g,""))===center){
					$el.animate({
						left: center-20+"px"
					}, 150, function(){
						$el.animate({
							left: center+"px"
						}, 150, function(){rotatingFlag=false;});
					});
				}
				else {
					$el.animate({
						left: center+"px"
					}, 300, function(){rotatingFlag=false;});
				}
			}
		}
	}
	function totalWidth(el){
		var elStyle=window.getComputedStyle(el,null);
		var elWidth=Number(elStyle.width.replace(/[^0-9\.-]/g,""));
		var elMarginRight=Number(elStyle.marginRight.replace(/[^0-9\.-]/g,""));
		var elMarginLeft=Number(elStyle.marginLeft.replace(/[^0-9\.-]/g,""));
		var elPaddingLeft=Number(elStyle.paddingLeft.replace(/[^0-9\.-]/g,""));
		var elPaddingRight=Number(elStyle.paddingRight.replace(/[^0-9\.-]/g,""));
		var elBorderLeftWidth=Number(elStyle.borderLeftWidth.replace(/[^0-9\.-]/g,""));
		var elBorderRightWidth=Number(elStyle.borderRightWidth.replace(/[^0-9\.-]/g,""));
		return elWidth+elPaddingLeft+elPaddingRight+elMarginRight+
			elMarginLeft+elBorderRightWidth+elBorderLeftWidth;
	}
	function totalHeight(el){
		var elStyle=window.getComputedStyle(el,null);
		var elHeight=Number(elStyle.height.replace(/[^0-9\.-]/g,""));
		var elMarginTop=Number(elStyle.marginTop.replace(/[^0-9\.-]/g,""));
		var elMarginBottom=Number(elStyle.marginBottom.replace(/[^0-9\.-]/g,""));
		var elPaddingTop=Number(elStyle.paddingTop.replace(/[^0-9\.-]/g,""));
		var elPaddingBottom=Number(elStyle.paddingBottom.replace(/[^0-9\.-]/g,""));
		var elBorderTopWidth=Number(elStyle.borderTopWidth.replace(/[^0-9\.-]/g,""));
		var	elBorderBottomWidth=Number(elStyle.borderBottomWidth.replace(/[^0-9\.-]/g,""));
		return elHeight+elPaddingTop+elPaddingBottom+elMarginTop+
			elMarginBottom+elBorderTopWidth+elBorderBottomWidth;
	}
	function isDescendant(e,descendant){
		if(e===descendant)return true;
		if(e.childNodes){
			for(var i=0;i<e.childNodes.length;i++){
				if(isDescendant(e.childNodes[i],descendant))return true;
			}
		}
		return false;
	}
	function abs(x){
		if(x>0)return x;
		return -x;
	}
	return {
		execute: function(){
			initSwipe();
			window.addEventListener('resize', centerEl);
			scheduler.executeLT();
		},
		finish: function(){
			resetSwipe();
			window.removeEventListener('resize', centerEl);
			scheduler.finishLT();
		}
	}
}
function Sort(scheduler, $args_obj){
	var t, tArgs, $window=jQuery(window);
	var prevWindowWidth=$window.width();
		//since this function is used initially and after every screen resize don't use it anonymously
		function initSort(){
			if((window.innerWidth?window.innerWidth:document.documentElement.clientWidth)>670){
				var $container=$args_obj.container;
				if($container.length>0){	//if there are containers on the page
					$container.each(function(){		//loop through the containers
						var $singleContainer=jQuery(this); //create a jquery object for the container
						var $items=$singleContainer.find('.tile'); //find all the tiles or icons in the container
						if($items.length>0){	//if there are tiles or icons
							/*
							 * This next line is important and necessary because it allows for the sorted row 
							 * to size for the entire container width. Otherwise the container can only get smaller
							 * by adding margins.
							 */
							$singleContainer.css({'position':'relative','width':'100%'});
							//get the container data and set up tracking variables
							//all css values have to be parsed to remove "px", etc. from the values
							var singleContainerWidth=Number($singleContainer.css('width').replace(/[^0-9\.-]/g,""));
							var runningTop=Number($singleContainer.css('top').replace(/[^0-9\.-]/g,""));
							var singleContainerLeft=Number($singleContainer.css('left').replace(/[^0-9\.-]/g,""));
							//runnning left and running top are the variables used to set the tile position
							//so initially they are set to the container upper left values
							var runningLeft=singleContainerLeft;
							//have to keep track of the max values so the container can be sized later (maxWidthRow)
								//and so that each row can be placed below the previous row (maxHeightRow)
							var maxHeightRow=0;
							var maxWidthRow=0;
							$items.each(function(){ //loop through each tile/icon
								var $item=jQuery(this); //get each as a jquery object
								//if the current tile to be placed will exceed container width 
									//this operation has to be performed before the tile is placed
								if(runningLeft+$item.totalWidth()>singleContainerWidth){
									runningTop+=maxHeightRow;	//move the tile down a row
									runningLeft=singleContainerLeft;	//move the tile to the first position in the row
									maxHeightRow=0; //set the max height of the row back to zero
									}
								//if the height of the current tile is greater than all the other tiles in it's row
								//this must be done after the calculation for surpassing the end of the row
									//so that max height isn't updated based on a tile not placed in that row (in case the 
								//tile isn't placed in that row
								if($item.totalHeight()>maxHeightRow){
										maxHeightRow=$item.totalHeight(); //update this tile height to the max row Height
								}
								//place the tile/icon
								$item.css({
									position:'absolute',
										top:runningTop+"px",
									left:runningLeft+"px"
								});
								//increment the current left position by the values for the current tile/icon
								//this has to be done after the tile is placed
								runningLeft+=$item.totalWidth();
								//if the current tile is placed further down a row than any other row
								//this has to be done after running left is updated.
								if(runningLeft>maxWidthRow){
									maxWidthRow=runningLeft; //update the max width of the row
								}
							});
							//finally add the last row height to the current top value
							//this is used for setting the container height
							runningTop+=maxHeightRow;
						}
						//size the container based on the width and height kept track of before
						$singleContainer.css({
							'width':maxWidthRow,
							'height':runningTop
						});
					});
				}
			}
		}
		function resetSort(){
			var $container=$args_obj.container;
			if($container.length>0){	//if there are containers on the page
				$container.each(function(){		//loop through the containers
					var $singleContainer=jQuery(this); //create a jquery object for the container
					var $items=$singleContainer.find('.tile'); //find all the tiles or icons in the container
					if($items.length>0){	//if there are tiles or icons
						$singleContainer.css({
							'position':"",
							'width':"",
							'height':""
						});
						$items.each(function(){
							jQuery(this).css({
							'position':"",
							'top':"",
							'left':"",
							});
						});
					}
				});
			}
		}
		function sortOnResize(){
			if($window.width()!==prevWindowWidth){	
				prevWindowWidth=$window.width();
				clearTimeout(t);
				t=setTimeout(initSort,200);
			}
		}
	return {
		execute: function(){
			initSort();
			$window.on('resize', sortOnResize);
			scheduler.executeGT();
		},
		finish: function(){
			resetSort();
			$window.off('resize', sortOnResize);
			scheduler.finishGT();
		},
		click: function($tile,index){
			if((window.innerWidth?window.innerWidth:document.documentElement.clientWidth)>670){
				initSort();
			}
			scheduler.executeAll(index);
		}
	}
}
function Rotator(scheduler, $args_obj){
	var slidestoshow=$args_obj.slidestoshow;
	var slidewidth=$args_obj.slidewidth;
	var slidemargin=$args_obj.slidemargin;
	var $window=jQuery(window);
	var tHolder= new Array();
	var resetFlag=false;
	var tInit;
	var moveCompletionFlag=true;
	var initFlag=false;
	function initSlide(){
		if(resetFlag){
			clearTimeout(tInit);
			tInit=setTimeout(initSlide, 400);
		}
		else {
			initFlag=true;
			var $containers=$args_obj.container;
			for(var k=0;k<$containers.length;k++){
				var $thisContainer=$containers.eq(k);
				var $slides = $thisContainer.find('li').css({
					'display':'none',
					'position':'absolute',
					'top':'0px',
					'width':slidewidth+"px",
					'margin': slidemargin+"px"
				});
				var index=0, timeout={t:""}, runningLeftDisplay=0, thisContainerWidth=$thisContainer.width(),
				indexList=new Array();
				var runningLeftHidden=thisContainerWidth;
				tHolder.push(timeout);
				for(var i=0;i<slidestoshow*2;i++){
					if(index>=$slides.length)index=0;
					indexList.push(index);
					index++;
				}
				for(var i=0;i<slidestoshow*2;i++){
					for(var j=0;j<i;j++){
						if(indexList[i]===indexList[j]){
							$slides.eq(indexList[i]).clone().appendTo($thisContainer.children('ul')).hide().addClass('cloned');
							indexList[i]=i;
							index=i+1;
							break;
						}
					}
				}
				$slides=$thisContainer.find('li');
				for(var i=0;i<slidestoshow*2;i++){
					var $slide=$slides.eq(indexList[i]);
					if(i<slidestoshow){
						$slide.css({
							'left':runningLeftDisplay+"px"
						}).show();
						runningLeftDisplay+=$slide.totalWidth();
					}
					else {
						$slide.css({
							'left':runningLeftHidden+"px"
						}).show();
						runningLeftHidden+=$slide.totalWidth();
					}
				}
				function move(){
					if(timeout.t!==null){
						moveCompletionFlag=true;
						clearTimeout(timeout.t);
						timeout.t=setTimeout(function(){		
							moveCompletionFlag=false;
							var completionFlag=0;
							for(var i=0;i<slidestoshow*2;i++){
								var $slide=$slides.eq(indexList[i]);
								$slide.animate({
									left: Number($slide.css('left').replace(/[^0-9\.-]/g,""))-thisContainerWidth+"px"
								}, 1000, function(){
									if(completionFlag===slidestoshow*2-1){
										for(var i=0;i<slidestoshow;i++){
											$slides.eq(indexList.shift()).hide();
										}
										for(var i=0;i<slidestoshow;i++){
											if(index>=$slides.length)index=0;
											indexList.push(index);
											index++;
										}
										runningLeftHidden=thisContainerWidth;
										for(var i=slidestoshow;i<slidestoshow*2;i++){
											var $slide=$slides.eq(indexList[i]);
											$slide.css({
												'left':runningLeftHidden+"px"
											}).show();
											runningLeftHidden+=$slide.totalWidth();
										}	
										runningLeftDisplay=0;
										move();
									}
									else completionFlag++;
								});
							}
						},$args_obj.scrollSpeed);
					}
					else {
						resetFlag=false;
						$slides.css({
							'left':"",
							'display':"",
							'position':"",
							'top':"",
							'width':"",
							'margin':""
						});
						$slides.filter('.cloned').remove();
					}				
				}
				setTimeout(move,$args_obj.delay);				
			}
		}
	}
	function resetSlide(){
		clearTimeout(tInit);
		if(initFlag){
			initFlag=false;
			resetFlag=true;
			var $containers=$args_obj.container;
			for(var i=0;i<$containers.length;i++){
				clearTimeout(tHolder[i].t);
				tHolder[i].t=null;
				tHolder.shift();
				if(moveCompletionFlag){
					resetFlag=false;
					$containers.eq(i).find('li').css({
						'left':"",
						'display':"",
						'position':"",
						'top':"",
						'width':"",
						'margin':""
					});
					$containers.eq(i).find('li').filter('.cloned').remove();
				}
			}
		}
	}
	return {
		finish: function(){
			resetSlide();
			scheduler.finishLT();
		},
		execute: function(){
			initSlide();
			scheduler.executeLT();
		}
	}
}
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
				var $target=jQuery(this.scheduleAll[i].target);
				if(this.scheduleAll[i].not){
					$target=$target.not(this.scheduleAll[i].not);
				}
				$target.on(this.scheduleAll[i].event,"",{index: i, objThis: this}, this.executeAll);
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
