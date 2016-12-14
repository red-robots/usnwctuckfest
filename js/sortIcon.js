jQuery(window).load(function(){
	//since this function is used initially and after every screen resize don't use it anonymously
	function sort(){
		var $container=jQuery('.icon.container');
		if($container.length>0){	//if there are containers on the page
			$container.each(function(){		//loop through the containers
				var $singleContainer=jQuery(this); //create a jquery object for the container
				var $items=$singleContainer.find('.icon'); //find all the tiles or icons in the container
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
			return true;
		}
		return false;
	}
	//call the function initially
	if(sort()){
		var t, $window=jQuery(window); //variable for timeout
		var prevWindowWidth=$window.width();
		//on resize clear timeout variable and prevent any previous resize from calling the sort function 
		//until no more resizes are called aka. the user stops resizing. Then call the sort function.
		//the effect is the sort function is called only once. 
		$window.on('resize',function(){
			if($window.width()!==prevWindowWidth){
				prevWindowWidth=$window.width();
				clearTimeout(t);
				t=setTimeout(sort,200);
			}
		});
	}
});