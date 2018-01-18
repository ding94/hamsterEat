var timer = null;
var stopScroll= false;
var ignoreScroll = false;
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
	/* start:
		code to display fixed navigation of category in restaurant details page. */
	scrNav();

	var height = $(window).scrollTop();

	if(height  > 200) {
	  $('#category-bar').addClass('fixed');
	}
	if (height < 250) {
	  $('#category-bar').removeClass('fixed');
	}
	/* end */
	if (ignoreScroll) return;
	$('.ajax-load').show();
    if($(window).scrollTop() + $(window).height() >= $(document).height()-500) 
    {
       	var dataID = new Array();
        $(".item").each(function(index, el) {
        	dataID.push($(this).data('id'));
        });
        ignoreScroll = true;
        setTimeout(function(){
            ignoreScroll = false;
            $.ajax({
			url: 'index.php?r=Restaurant/default/load-more-food',
			data: { id: dataID} ,
			dataType: 'json',
			beforeSend: function()
            {
                $('.ajax-load').show();
            }
		})
		.done(function(data) {
			console.log(data);
			
			if(data.value == 2)
			{
				$('.menu-container').append(data.message);
				$('.stars').singleStar();
				
			}
			else if(data.value == 3)
			{
				ignoreScroll = true;
				$(window).unbind('scroll');
			}
			$('.ajax-load').hide();
			console.log(i++);
		})
		.fail(function(e) {
				console.log(e);
		});
        }, 1000);
	    
    }
    
 });

function stopNow()
{
	if(stopScroll === true)
	{
		$(window).scroll().off();
	}
}

