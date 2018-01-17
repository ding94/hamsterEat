$(window).scroll(function() {
    var height = $(window).scrollTop();

    if(height  > 200) {
      $('#category-bar').addClass('fixed');
    }
    if (height < 250) {
      $('#category-bar').removeClass('fixed');
    }
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