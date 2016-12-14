var $window=jQuery(window);
$window.load(function(){
	centerLanding();
	$window.on('resize',centerLanding);
});

function centerLanding(){
	var $landing=jQuery('div.landing');
	var marginTop=$window.height()/2-$landing.height()/2;
	if(marginTop<0)marginTop=0;
	$landing.css({
		'marginTop':marginTop+"px"
	});
}