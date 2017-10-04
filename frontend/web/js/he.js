
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