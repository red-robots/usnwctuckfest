function Rotator(scheduler){
	var slidestoshow=3;
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
		var $containers=jQuery('.rotator.container');
		for(var k=0;k<$containers.length;k++){
			var $thisContainer=jQuery($containers[k]);
			var $slides = $thisContainer.find('li').css({
				'display':'none',
				'position':'absolute',
				'top':0
			});
			var index=0, timeout={t:""}, runningLeft=0, widthList=new Array(), indexList=new Array();
			tHolder.push(timeout);
			for(var i=0;i<slidestoshow*2;i++){
				if(index>=$slides.length)index=0;
				indexList.push(index);
				index++;
			}
			for(var i=0;i<slidestoshow*2;i++){
				for(var j=0;j<i;j++){
					if(j!==i&&indexList[i]===indexList[j]){
						$slides.eq(indexList[i]).clone().appendTo($thisContainer).hide();
						break;
					}
				}
			}
			for(var i=0;i<slidestoshow*2;i++){
				var $slide=$slides.eq(indexList[i]);
				$slide.css({
					'left':runningLeft+"px"
				}).show();
				widthList.push($slide.totalWidth());
				runningLeft+=widthList[widthList.length-1];
			}
			move();
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
								left: Number($slide.css('left').replace(/[^0-9\.-]/g,""))-$thisContainer.width()+"px"
							}, 1000, function(){
								if(completionFlag===slidestoshow*2-1){
									for(var k=0;k<slidestoshow;k++){
										$slides.eq(indexList.shift()).hide();
										runningLeft-=widthList.shift();
									}
									for(var i=0;i<slidestoshow;i++){
										if(index>=$slides.length)index=0;
										indexList.push(index);
										index++;
									}
									for(var i=slidestoshow;i<slidestoshow*2;i++){
										var $slide=$slides.eq(indexList[i]);
										$slide.css({
											'left':runningLeft+"px"
										}).show();
										widthList.push($slide.totalWidth());
										runningLeft+=widthList[widthList.length-1];
									}	
									move();
								}
								else completionFlag++;
							});
						}
					},4000);
				}
				else {
					resetFlag=false;
					$slides.css({
						'left':"",
						'display':"",
						'position':"",
						'top':""
					});
				}				
			}
		}
		}
	}
	function resetSlide(){
		clearTimeout(tInit);
		if(initFlag){
		initFlag=false;
		resetFlag=true;
		var $containers=jQuery('.rotator.container');
		for(var i=0;i<$containers.length;i++){
			clearTimeout(tHolder[i].t);
			tHolder[i].t=null;
			tHolder.shift();
			if(moveCompletionFlag){
				resetFlag=false;
				jQuery($containers[i]).find('li').css({
					'left':"",
					'display':"",
					'position':"",
					'top':""
				});
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
