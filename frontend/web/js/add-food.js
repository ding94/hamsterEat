/*function changePrice(){
    price =$('#afterprice').val();
    console.log('a');
    $('#price').val((price/1.3).toFixed(2));
};
*/
$('#afterprice').change(function(){
    price =$(this).val();
   
    $('#price').val((price/1.3).toFixed(2));
});

$("#price").change(function(){
    price = $(this).val();
    $('#afterprice').val((price*1.3).toFixed(2));
})

function markUp(type)
{
    $('.selectionTable').children('tbody').each(function(){
        trChild = $(this).children('tr');
        trChild.each(function(){
            if(type == 1)
            {
                value = $(this).children('.selectionPrice').children('.form-group').children('input').val();
                $(this).children('.selectionBefore').children('.form-group').children('input').val((value/1.3).toFixed(2));
            }
            else
            {
                value = $(this).children('.selectionBefore').children('.form-group').children('input').val();
            $(this).children('.selectionPrice').children('.form-group').children('input').val((value*1.3).toFixed(2));
            }
           
        })
    });
}

