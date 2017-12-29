/* JS function for modal creation in food menu */
$(function(){

    $('#foodDetail').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
           
            var href = button.attr('href');
            var mutipleImg = jQuery.parseJSON(button.attr('data-img'));
            var count = Object.keys(mutipleImg).length;
            var imgslide = '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
            for (i = 0; i < count; i++){
                imgslide += '<img class="mySlides" src="'+mutipleImg[i]+'">';
            }
            imgslide += '<div class="left-container" onclick="plusDivs(-1)"><div class="w3-left">&#10094;</div></div><div class="right-container" onclick="plusDivs(1)"><div class="w3-right">&#10095;</div></div><div class="bottom">';
            for(i = 0; i < count; i++){
                imgslide += '<span class="dots w3-border"></span>';
            }
            imgslide += '</div>';
            modal.find('.modal-body').html('<i class=\"fa fa-spinner fa-spin\"></i>');
            $.post({url : href, async: true, backdropLimit: 1})
                .done(function( data ) {
                    modal.find('.modal-body').html(data);
                    modal.find('.modal-header').html(imgslide);
                    var slideIndex = 1;
                    showDivs(slideIndex);

                    function plusDivs(n) {
                        showDivs(slideIndex += n);
                    }

                    function showDivs(n) {
                        var i;
                        var x = document.getElementsByClassName("mySlides");
                        var dots = document.getElementsByClassName("dots");
                        if (n > x.length) {slideIndex = 1} 
                        if (n < 1) {slideIndex = x.length} ;
                        for (i = 0; i < x.length; i++) {
                            x[i].style.display = "none"; 
                        }
                        for (i = 0; i < dots.length; i++) {
                           dots[i].className = dots[i].className.replace(" w3-white", "");
                        }
                        x[slideIndex-1].style.display = "block"; 
                        dots[slideIndex-1].className += " w3-white";
                    }
                });
            });



    

    /* $('#foodDetail').click(function(e){
         e.preventDefault();
         var foodid = $(this).attr('data-id'); 
         console.log($(this)); 
         var modelContent = '#modelContent'+foodid;
         var modal = '#modal'+foodid;
         $(modal).modal('show')
             .find(modelContent)
             .load($(this).attr('href'));
     });
*/
});

var slideIndex = 1;

function plusDivs(n) {
    showDivs(slideIndex += n);
}

function showDivs(n) {
    var i;
    var x = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dots");
    if (n > x.length) {slideIndex = 1} 
    if (n < 1) {slideIndex = x.length} ;
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none"; 
    }
    for (i = 0; i < dots.length; i++) {
       dots[i].className = dots[i].className.replace(" w3-white", "");
    }
    x[slideIndex-1].style.display = "block"; 
    dots[slideIndex-1].className += " w3-white";
}
