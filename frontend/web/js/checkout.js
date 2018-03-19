$(document).ready(function(){
    if($("#nameConfirm").length)
    {
        if(confirm('Your User Full Name and Contanct Does Not Same With Your Order Detail.Do You Want To Replace It?'))
        {
            name = $("input[name='name']").val();
            contactno = $("input[name='contactno']").val();
            url = $("input[name='url']").val();
            console.log(url);
            $.ajax({
                url: url,
                type: 'POST',
                data :{name: name,contactno : contactno},
            success: function (data) {
                obj = JSON.parse(data);
                if(obj.value == 1)
                {
                    $('#w0-success-0').append("</br>"+obj.message).fadeIn();
                    //$('#system-messages').children().delay(3000).fadeTo(500,0).slideUp(500).queue(function() { $('#aa').remove(); });
                }
            },
            error: function (request, status, error) {
              console.log(request.responseText);
            }

            });
            
        }
       
    }
})

$(function(){
    $('#address-modal').on('show.bs.modal', function (event) {
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

$(function(){
    $('#edit-address-modal').on('show.bs.modal', function (event) {
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

$('#deliveryaddress-cid input').on('change', function(event) {
    event.preventDefault();
    if($(this).val() == 0)
    {
       $('.address.none').removeClass('none');
    }
    else
    {
        $(".address:not([class*='none'])").addClass('none');      
    }
});

$("input[name='DeliveryAddress[location]']").on('change',function(){
    id = $(this).val();
    $.ajax({
            url :"index.php?r=cart/getaddress",
            type: "get",
            data:{
                addr : id,
        },
        success: function (data){
            var obj = JSON.parse(data);
            $("input[name='DeliveryAddress[name]']").val(obj.recipient);
            $("input[name='DeliveryAddress[contactno]']").val(obj.contactno);

        },
        error: function (request, status, error) {
            console.log(request.responseText);
       }
    })

})

 

