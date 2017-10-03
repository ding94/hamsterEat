
$(document).ready(function() {
	$('.element1').addClass('bounce');
	window.setTimeout(function(){
        $('.element2').addClass('bounce');
    }, 1500);
    window.setTimeout(function(){
        $('.element3').addClass('bounce');
    }, 3000);
});