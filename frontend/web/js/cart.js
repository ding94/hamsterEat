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

  function discount()
  {   
    $.ajax({
    url :"index.php?r=cart/getdiscount",
    type: "get",
    data :{
      dis: document.getElementById("voucherstype-type").value.replace(/\s+/g, ''),
      sub: parseFloat(document.getElementById("subtotal").innerHTML).toFixed(2),
      deli: parseFloat(document.getElementById("delivery").innerHTML).toFixed(2),
      total: parseFloat(document.getElementById("total").innerHTML).toFixed(2),
    },
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj == 19) { return false;}
      if (obj != 0 ) 
      {
        
        document.getElementById("disamount").innerHTML = "- "+(parseFloat(obj['discount'])).toFixed(2);
        document.getElementById("discount").style = "display:block;";
        
  
        document.getElementById("early").innerHTML = "- "+(0).toFixed(2);
        document.getElementById("subtotal").innerHTML = (parseFloat(obj['sub'])).toFixed(2);
        document.getElementById("delivery").innerHTML = (parseFloat(obj['deli'])).toFixed(2);
        document.getElementById("total").innerHTML = (obj['total']).toFixed(2);
        $("input[name='code']").val(document.getElementById("voucherstype-type").value.replace(/\s+/g,''));

        document.getElementById("voucher").style ='display:none';
        document.getElementById("refresh").style ='display:block';
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
