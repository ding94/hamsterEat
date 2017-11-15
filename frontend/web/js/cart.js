$(function(){
    $('#add-modal').on('show.bs.modal', function (event) {
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

function showHidden()
  {
      document.getElementById("label").style.display ='block';
      document.getElementById("input").style.display ='block';
      document.getElementById("apply").style.display ='block';
      document.getElementById("hide2").style.display ='none';
  }

  function discount()
  { 

    $.ajax({
   url :"index.php?r=cart/getdiscount",
   type: "get",
   data :{
        dis: document.getElementById("input").value.replace(/\s+/g, ''),
        did:  $('input.did').val(),
   },
   success: function (data) {
    
      var obj = JSON.parse(data);
       if (obj != 0 ) 
      {
        document.getElementById("subtotal").innerHTML = (obj['Orders_Subtotal']).toFixed(2);
        document.getElementById("delivery").innerHTML = (obj['Orders_DeliveryCharge']).toFixed(2);
        document.getElementById("total").innerHTML = (obj['Orders_TotalPrice'] + parseFloat(document.getElementById("early").innerHTML)).toFixed(2);

        document.getElementById("label").style.display ='none';
        document.getElementById("input").style.display ='none';
        document.getElementById("apply").style.display ='none';
        document.getElementById("reset").style.display ='block';
        document.getElementById("orders-orders_totalprice").value = document.getElementById("input").value;
      }

      else if (obj ==0) 
      {
        alert("No coupon found or coupon expired! Please check your account > Discount Codes");
      }
   },
   error: function (request, status, error) {
    //alert(request.responseText);
   }

   });
  }

  function refresh()
  {
    location.reload();
  }
