
$(document).ready(function(){
	
	//Check to see if the window is top if not then display button
	$(window).scroll(function(){
		if ($(this).scrollTop() > 50) {
			$('.scrollToTop').fadeIn();
		} else {
			$('.scrollToTop').fadeOut();
		}
	});
	
	//Click event to scroll to top
	$('.scrollToTop').click(function(){
		$('html, body').animate({scrollTop : 0},500);
		return false;
	});

	 $('.toggle').click(function(event){
        event.stopPropagation();
         $(".filter").slideToggle("slow");
    });
    $(".toggle").on("click", function (event) {
        event.stopPropagation();
    });
});


$(document).on("click", function () {
    $(".filter").hide();
});
