function checkempty()
    {
        if (document.getElementsByName("Orders[Orders_PaymentMethod]")[1].checked) 
        {
            var aler = "Are you sure to pay with Account Balance?";
        }
        else if (document.getElementsByName("Orders[Orders_PaymentMethod]")[2].checked)
        {
            var aler = "Are you sure to pay cash on delivery?";
        }
        else
        {
            alert('Please select a payment method!');
            return false;
        }

        var con = confirm(aler);
        if (con == true) { return true;}
        else {return false;}
    }

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

 

