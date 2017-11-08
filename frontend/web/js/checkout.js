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
