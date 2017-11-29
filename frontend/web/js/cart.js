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

$('footer.content').on('click', '.qt-plus', function(event) {
  event.preventDefault();
  $(this).attr("disabled", true);
  parent = $(this).parent();
  cid = parent.children("input[name=id]").val();
  
  quantity("plus",cid)
  .done(function(data){
    //var obj = JSON.parse(data);
      if (data.value == 0) {
          alert(data.message);
      }
      else
      {
          parent.children('#qt').text(data.message.quantity);
          total = data.message.quantity*data.message.price;
          parent.children('.full-price').text("RM "+total.toFixed(2));
          document.getElementById('iframe').contentWindow.location.reload();
          $(this).attr("disabled", false);
      }  
    }) 
  .fail(function(e){
    console.log(e);
    $(this).attr("disabled", false);
  })
});

$('footer.content').on('click', '.qt-minus', function(event) {
  event.preventDefault();
  $(this).attr("disabled", true);
  parent = $(this).parent();
  cid = parent.children("input[name=id]").val();
  quantity("minus",cid)
  .done(function(data){
    //var obj = JSON.parse(data);
      if (data.value == 0) {
          alert(data.message);
      }
      else
      {
          parent.children('#qt').text(data.message.quantity);
          total = data.message.quantity*data.message.price;
          parent.children('.full-price').text("RM "+total.toFixed(2));
          document.getElementById('iframe').contentWindow.location.reload();
      }
      $(this).attr("disabled", false);
    }) 
  .fail(function(e){
    console.log(e);
     $(this).attr("disabled", false);
  })
});

function quantity(up,cid)
{
  return $.ajax({
    url: "index.php?r=cart/quantity",
    type: "get",
    data: {
      update: up,
      cid: cid,
    },
    dataType: 'json',
  });
}


  function discount()
  {   
    $.ajax({
    url :"index.php?r=cart/getdiscount",
    type: "get",
    data :{
      dis: document.getElementById("voucherstype-type").value.replace(/\s+/g, ''),
      codes: document.getElementById("codes").value.replace(/\s+/g, ''),
      sub: parseFloat(document.getElementById("subtotal").innerHTML).toFixed(2),
      deli: parseFloat(document.getElementById("delivery").innerHTML).toFixed(2),
      total: parseFloat(document.getElementById("total").innerHTML).toFixed(2),
    },
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj == 19) { return false;}
      if (obj != 0 ) 
      {
        //document.getElementById("disamount").innerHTML = "- "+(parseFloat(obj['discount'])).toFixed(2);
        
       // document.getElementById("discount").style = "display:block;";
		discout = (parseFloat(obj['discount'])).toFixed(2);
        document.getElementById("early").innerHTML = "- "+(0).toFixed(2);
        document.getElementById("subtotal").innerHTML = (parseFloat(obj['sub'])).toFixed(2);
        document.getElementById("delivery").innerHTML = (parseFloat(obj['deli'])).toFixed(2);
        document.getElementById("total").innerHTML = (obj['total']).toFixed(2);
        $("input[name='code']").val(obj['code'].replace(/\s+/g,''));

        document.getElementById("voucher").style ='display:none';
        document.getElementById("refresh").style ='display:block';
		$('.table-total tr:nth-child(2)').after("<tr><td><b>Discount</b></td><td class='text-xs-left'>-"+discout+"</td></tr>");
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

  function show()
  {
    document.getElementById("voucherstype-type").style ='display:none';
    document.getElementById("show").style = "display:none";
    document.getElementById("dis").style = "display:block";
  }

  function show2()
  {
    document.getElementById("show").style = "display:none";
    document.getElementById("dis").style = "display:block";
  }

  function refresh()
  {
    location.reload();
  }
