(function($){$.fn.equalHeights=function(minHeight,maxHeight){tallest=(minHeight)?minHeight:0;this.each(function(){if($(this).height()>tallest){tallest=$(this).height()}});if((maxHeight)&&tallest>maxHeight)tallest=maxHeight;return this.each(function(){$(this).height(tallest)})}})(jQuery)
	$(window).load(function(){
		if($(".col-xs-12 .minheight").length){
		$(".col-xs-12 .minheight").equalHeights()}
	});
	
$(document).ready(function() {
    $uniformed = $("#header").find("select");
	$uniformed.uniform();
});