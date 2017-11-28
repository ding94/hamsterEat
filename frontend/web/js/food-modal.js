/* JS function for modal creation in food menu */
$(function(){

    $('#foodDetail').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            var href = button.attr('href');
            var img = '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><img class=\"img-rounded img-responsive detail-img\" src=\"./../web/imageLocation/foodImg/'+button.attr('data-img')+'\" alt=\"\" ">';
            modal.find('.modal-body').html('<i class=\"fa fa-spinner fa-spin\"></i>');
            $.post(href)
                .done(function( data ) {
                    modal.find('.modal-body').html(data);
                    modal.find('.modal-header').html(img);
                
                });
            });

    // $('.modelButton').click(function(e){
    //     e.preventDefault();
    //     var foodid = $(this).attr('data-id');
    //     var modelContent = '#modelContent'+foodid;
    //     var modal = '#modal'+foodid;
    //     $(modal).modal('show')
    //         .find(modelContent)
    //         .load($(this).attr('href'));
    // });

});