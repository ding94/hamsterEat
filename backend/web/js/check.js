$('.check-all').click(function() {
    var selector = $(this).is(':checked') ? ':not(:checked)' : ':checked';

    console.log($(this).attr('id'));
    $('.'+$(this).attr('id')).find('input[type="checkbox"]' + selector).each(function() {
        $(this).trigger('click');
    });
});