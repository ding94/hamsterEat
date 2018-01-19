var timer = null;
var ignoreScroll = false;

$(document).ready(function(){
	var win = $(window);
	if($("input[name=moreFood]").val() == 0)
	{
		ignoreScroll = true;
		$(window).unbind('scroll');
	} 
	win.scroll(function(event) {
		/* Act on the event */
		event.preventDefault();
		event.stopImmediatePropagation();

		if (ignoreScroll) return;

		footerheight = $(document).height()/2;
		windowheight = $(window).scrollTop() + $(window).height();

		if(windowheight >= footerheight){
			ignoreScroll = true;
			$('.ajax-load').show();
			limit = countGrid();
			
			dataID = allitemID();	
			
			
			setTimeout(function(){
				
				$.ajax({
					url: 'index.php?r=Restaurant/default/load-more-food',
					dataType: 'json',
					data: { id: dataID,limit:limit},
				})
				.done(function(data) {
					console.log(data);
					if(data.value == 2 || data.value == 4)
					{
						ignoreScroll = false;
						$('.menu-container').append(data.message);
						singleStar(limit);
						if(data.value == 4)
						{
							$(window).unbind('scroll');
						}	
					}
					else if(data.value == 3)
					{
						ignoreScroll = true;
						$(window).unbind('scroll');
					}
					$('.ajax-load').hide();
				})
				.fail(function(e) {
					console.log(e);
				})
			},1500);
		}
	});
});

function countGrid()
{
	word = $(".menu-container").css('grid-template-columns');
	res = word.split(" ");
	limit = res.length;
	if(limit <= 2)
	{
	    limit =2;
	}
	return limit+limit;
}

function allitemID()
{
	var dataID = new Array();
	$(".item").each(function(index, el) {
	    dataID.push($(this).data('id'));
	});
	return dataID;
}
