function Touchable(scheduler){
	var $window = jQuery(window);
	return {
		execute: function(){
			var $containers= jQuery('.touchable.tile.container');
			$containers.each(function(){
				var $singleContainer = jQuery(this);
				var runningHeight=0;
				$singleContainer.children('.tile.touchable').each(function(){
					var tileHeight=jQuery(this).totalHeight();
					if(tileHeight>runningHeight)runningHeight=tileHeight;
				});
				$singleContainer.height(runningHeight);
			});
			scheduler.executeLT();
		},
		finish: function(){
			scheduler.finishLT();
		},
		click: function($tile,index){
			$tile.children('section.copy').toggle(200, function(){
				if($window.width()<=670){
					$tile.parent().height($tile.totalHeight());
				}
				scheduler.executeAll(index);
			});
		}
	}
}