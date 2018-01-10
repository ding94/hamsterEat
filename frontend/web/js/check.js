$('.check-all').click(function() {
    var selector = $(this).is(':checked') ? ':not(:checked)' : ':checked';

    if($(this).is('#order'))
   	{
   		div = $(this).closest('.outer-div');
   		table = div.children('.table');
   	}
   	else
   	{
   		table = $(this).closest('.table');
   	}
  
    table.children('tbody').find('input[type="checkbox"]' + selector).each(function() {
        $(this).trigger('click');
    });
});