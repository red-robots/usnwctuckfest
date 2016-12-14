function Swipe(scheduler){
	var t, prevInnerWidth=window.innerWidth;
	function initSwipe(){
		var containers=document.getElementsByClassName('slideable container');
		for(var k=0;k<containers.length;k++){
			var els=containers[k].getElementsByClassName('slideable');
			var runningHeight=0;
			var activeTileIndex=0;
			var contStyle=window.getComputedStyle(containers[k],null);
			for(var i=0;i<els.length;i++){
				if(els[i].className.replace(/\bactive\b/gi,"")){
					activeTileIndex=i;
					break;
				}
			}	
			for(var i=0;i<els.length;i++){
				var el=els[i];
				var elHeight=totalHeight(el);
				if(i===activeTileIndex){
					el.style.left=Number(contStyle.width.replace(/[^0-9\.-]/g,""))/2
						-totalWidth(el)/2+"px";
					el.style.display="block";
					el.className=el.className.replace(/\bactive\b\s*/gi,"").replace(/\b\s*$/gi,"")+" active";
				}
				else {
					el.style.display="none";
					el.className=el.className.replace(/\bactive\b\s*/gi,"").replace(/\b\s*$/gi,"");
				}
				if(elHeight>runningHeight){
					runningHeight=elHeight;
				}
				el.style.position="absolute";
				el.style.top=0+"px";
				scope(el);
			}
			containers[k].style.height=runningHeight+"px";
			var rightArrow=document.createElement("img");
			var leftArrow=document.createElement("img");
			var arrowLoadNum=0;
			rightArrow.src="/wp-content/uploads/2015/09/Right_Slider_Arrow.png";
			rightArrow.className="right arrow";
			rightArrow.style.position="absolute";			
			leftArrow.src="/wp-content/uploads/2015/09/Left_Slider_Arrow.png";
			leftArrow.className="left arrow";
			leftArrow.style.position="absolute";
			containers[k].appendChild(leftArrow);
			containers[k].appendChild(rightArrow);
			rightArrow.addEventListener("load", setArrows, false);
			leftArrow.addEventListener("load", setArrows, false);
			function setArrows(){
				arrowLoadNum++;
				if(arrowLoadNum===2){
					leftArrow.style.top= Number(contStyle.height.replace(/[^0-9\.-]/g,""))/2-totalHeight(leftArrow)/2+"px";
					leftArrow.style.left= "0px";
					rightArrow.style.top= Number(contStyle.height.replace(/[^0-9\.-]/g,""))/2-totalHeight(rightArrow)/2+"px";
					rightArrow.style.left= Number(contStyle.width.replace(/[^0-9\.-]/g,""))-totalWidth(rightArrow)+"px";
					leftArrow.addEventListener("click", rotateRight, false);
					rightArrow.addEventListener("click", rotateLeft, false);
				}
			}
		}
	}
	function resetSwipe(){
		var containers=document.getElementsByClassName('slideable container');
		for(var k=0;k<containers.length;k++){
			var els=containers[k].getElementsByClassName('slideable');
			containers[k].style.height="";
			for(var i=0;i<els.length;i++){
				var el=els[i];
				scope(el);
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
			if(window.innerWidth<=670&&window.innerWidth>=320){
				t=setTimeout(function(){
					if(window.innerWidth<=670&&window.innerWidth>=320){
						var $containers=jQuery('.slideable.container')
						$containers.each(function(){
							var $singleContainer=jQuery(this);
							var $els=$singleContainer.find('.slideable.active');
							$els.each(function(){
								$el=jQuery(this);
								$el.css({
									'left': (window.innerWidth/2)-($el.totalWidth()/2)+"px"
								});
							});
							var $arrowLeft=$singleContainer.children('.left.arrow');
							var $arrowRight=$singleContainer.children('.right.arrow');
							$arrowLeft.css({
								'left': "0px",
								'top': $singleContainer.height()/2-$arrowLeft.totalHeight()/2+"px"
							});
							$arrowRight.css({
								'left': $singleContainer.width()-$arrowRight.totalWidth()+"px",
								'top': $singleContainer.height()/2-$arrowRight.totalHeight()/2+"px"
							});
						});
					}
				}, 200);
			}
		}
	}
	function scope(el){
		if(window.innerWidth>670){
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
			var $prevEl= $els.eq(elIndex-1);
			if($prevEl.length!==0){
				var ww=jQuery(window).width();
				$prevEl.css({
					'left':$container.width()/2-$prevEl.totalWidth()/2-ww+"px",
					'display':'block'
				}).show().animate({
					left: Number($prevEl.css('left').replace(/[^0-9\.-]/g,""))+ww+"px",
				}, 300, "swing", function(){
					$container.height($prevEl.totalHeight());
				}).addClass('active');
				$el.animate({
					left: Number($el.css('left').replace(/[^0-9\.-]/g,""))+ww+"px",
				}, 300, "swing", function(){
					$el.hide();
				}).removeClass('active');
			}
			else animateCenter();
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
					}, 150);
				});
			}
			else {
				$el.animate({
					left: center+"px"
				}, 300);
			}
		}
	}
	function rotateLeft(e){	
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
			var $nextEl= $els.eq(elIndex+1);
			if($nextEl.length!==0){
					var ww=jQuery(window).width();
					$nextEl.css({
						'left':$container.width()/2-$nextEl.totalWidth()/2+ww+"px"
					}).show().animate({
						left: Number($nextEl.css('left').replace(/[^0-9\.-]/g,""))-ww+"px",
					}, 300, "swing", function(){
						$container.height($nextEl.totalHeight());
					}).addClass('active');
					$el.animate({
						left: Number($el.css('left').replace(/[^0-9\.-]/g,""))-ww+"px",
					}, 300, "swing", function(){
						$el.hide();
					}).removeClass('active');
			}
			else animateCenter();
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
					}, 150);
				});
			}
			else {
				$el.animate({
					left: center+"px"
				}, 300);
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
			if(TouchEvent){
				initSwipe();
				window.addEventListener('resize', centerEl);
			}
			scheduler.executeLT();
		},
		finish: function(){
			if(TouchEvent){
				resetSwipe();
				window.removeEventListener('resize', centerEl);
			}
			scheduler.finishLT();
		}
	}
}