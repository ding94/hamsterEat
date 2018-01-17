var timer = null;
var stop = false;
var i =0;
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


});


$(window).scroll(function(e) {
	e.preventDefault();
	e.stopImmediatePropagation();
	if(timer !== null) {
        clearTimeout(timer);        
    }

    timer = setTimeout(function() {
          // do something
          if($(window).scrollTop() + $(window).height() >= $(document).height()-500) {
    	var time = $(".item:last").attr("data-id");
    	$.ajax({
			url: 'index.php?r=Restaurant/default/load-more-food',
			data: { time: time} ,
			dataType: 'json',
			
		})
		.done(function(data) {
			
			if(data.value == 2)
			{
			
					$('.menu-container').append(data.message);
				
				
			}
			else if(data.value == 3)
			{
				stop = true;
				$(window).scroll().off();
			}
			console.log(i++);
		})
		.fail(function(e) {
			 console.log(e);
		});
    }
    }, 150);
    

 });

