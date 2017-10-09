
$(document).ready(function() {
	setInterval(function() {
  $('.element1').addClass('bounce');
	window.setTimeout(function(){
        $('.element2').addClass('bounce');
    }, 1500);
    window.setTimeout(function(){
        $('.element3').addClass('bounce');
    }, 3000);
    window.setTimeout(function(){
        $('.element1').removeClass('bounce');
        $('.element2').removeClass('bounce');
        $('.element3').removeClass('bounce');
    }, 5000);
}, 7000);
		
});


//effect for text changing
var cnt=0, texts=[];
var $fclick = false;


$(".imawhat").each(function() {
  texts[cnt++]=$(this).text();
});

function fadeText() {
  if (cnt>=texts.length) { cnt=0; }
  $('.ima').html(texts[cnt++]);
  $('.ima')
    .fadeIn('fast').animate({opacity: 1.0}, 5000).fadeOut('fast',
     function() {
       return fadeText();
     }
  );
}


fadeText();


/* JS function for modal creation in food menu */
$(function(){
    $('.modelButton').click(function(e){
        e.preventDefault();
        var foodid = $(this).attr('data-id');
        var modelContent = '#modelContent'+foodid;
        var modal = '#modal'+foodid;
        $(modal).modal('show')
            .find(modelContent)
            .load($(this).attr('value'));
    });
});

$.fn.stars = function() {
    return $(this).each(function() {
        // Get the value
        var val = parseFloat($(this).html());
        // Make sure that the value is in 0 - 5 range, multiply to get width
        var size = Math.max(0, (Math.min(5, val))) * 16;
        // Create stars holder
        var $span = $('<span />').width(size);
        // Replace the numerical value with stars
        $(this).html($span);
    });
}

$(function() {
    $('span.stars').stars();
});