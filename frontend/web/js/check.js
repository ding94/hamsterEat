$('.check-all').click(function() {
    var selector = $(this).is(':checked') ? ':not(:checked)' : ':checked';

    table = $(this).closest('.table');
  
    table.children('tbody').find('input[type="checkbox"]' + selector).each(function() {
        $(this).trigger('click');
    });
});