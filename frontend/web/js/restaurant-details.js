$(document).ready(function(){
	$(window).scroll(function() {
		scrNav();

	    var height = $(window).scrollTop();

	    if(height  > 200) {
	      $('#category-bar').addClass('fixed');
	    }
	    if (height < 250) {
	      $('#category-bar').removeClass('fixed');
	    }
	})
});

$('.scroll-link').on('click', function(event){
    event.preventDefault();
    var sectionID = $(this).attr("data-id");
    scrollToID('#' + sectionID, 750);
    });

function scrollToID(id, speed){
    var targetOffset = $(id).offset().top - $("#category-bar").outerHeight()*3;
    $('html,body').animate({scrollTop:targetOffset}, speed);
}

var link = $('#category-bar a.scroll-link');

function scrNav() {
    var sTop = $(window).scrollTop();
    $('.foodtype').each(function() {
      var id = $(this).attr('id'),
          offset = $(this).offset().top - $("#category-bar").outerHeight()*3,
          height = $(this).height();
      if(sTop >= offset && sTop < offset + height) {
        link.removeClass('active-link');
        $('#category-bar').find('[data-id="' + id + '"]').addClass('active-link');
      }
    });
}