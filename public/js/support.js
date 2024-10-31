$(document).ready(function(){
	$('body').delegate('.card-header','click',function(){
		if($(this).hasClass('active') == true){
			$('.card-header').removeClass('active');
		} else{
			$('.card-header').removeClass('active');
			$(this).addClass('active');
		}
	});
});