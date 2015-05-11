$(document).ready(function() {
	var width = $('.left').width();
	$( window ).resize(function() { // when window resize
		var width = $('.left').width();
		if($( window ).width() <=1100)	
			$('.right').css({"maxWidth":width+'px'});
		else	
			$('.right').css({"maxWidth":'360px'});
	});	
		if($( window ).width() <=1100)	
			$('.right').css({"maxWidth":width+'px'});
		else	
			$('.right').css({"maxWidth":'360px'});
	$('#top-reviews').click(function(e){
		e.preventDefault();
		$('#topmenu ul li#showcase').removeClass('activeMenu');
		$('#topmenu ul li#top-reviews').addClass('activeMenu');
		//m_showreviews();
	})
	$('#showcase').click(function(e){
		e.preventDefault();
		$('#topmenu ul li#top-reviews').removeClass('activeMenu');
		$('#topmenu ul li#showcase').addClass('activeMenu');
	});		
});