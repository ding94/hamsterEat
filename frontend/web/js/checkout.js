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
            document.getElementById("list").style.color ="red";
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

$("input[name='Orders[Orders_Location]']").on('change',function(){
    id = $(this).val();
    $.ajax({
            url :"index.php?r=cart/getaddress",
            type: "get",
            data:{
                addr : id,
        },
        success: function (data){
            var obj = JSON.parse(data);
            $("input[name='Orders[User_fullname]']").val(obj.recipient);
            $("input[name='Orders[User_contactno]']").val(obj.contactno);

        },
        error: function (request, status, error) {
            console.log(request.responseText);
       }
    })

})

 

