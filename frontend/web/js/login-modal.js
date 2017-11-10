$(function(){
    $('#login-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            var href = button.attr('href');
            modal.find('.modal-body').html('<i class=\"fa fa-spinner fa-spin\"></i>');
            $.post(href)
                .done(function( data ) {
                    modal.find('.modal-body').html(data);
                });
            });
});