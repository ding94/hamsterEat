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
        
        document.getElementById("dissub").innerHTML = (document.getElementById("subtotal").innerHTML - parseFloat(obj['sub'])).toFixed(2);
        if (document.getElementById("dissub").innerHTML >= 0) {document.getElementById("dissub").style = 'display:block;color:red;';}
        document.getElementById("disdel").innerHTML = (document.getElementById("delivery").innerHTML - parseFloat(obj['deli'])).toFixed(2);
        if (document.getElementById("disdel").innerHTML >= 0) {document.getElementById("disdel").style = 'display:block;color:red;';}
        document.getElementById("distol").innerHTML = (document.getElementById("total").innerHTML - parseFloat(obj['total'])).toFixed(2);
        if (document.getElementById("distol").innerHTML >= 0) {document.getElementById("distol").style = 'display:block;color:red;';}
        
  
        document.getElementById("subtotal").innerHTML = (parseFloat(obj['sub'])).toFixed(2);
        document.getElementById("delivery").innerHTML = (parseFloat(obj['deli'])).toFixed(2);
        document.getElementById("total").innerHTML = (obj['total'] + parseFloat(document.getElementById("early").innerHTML)).toFixed(2);
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