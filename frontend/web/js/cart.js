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

function detectEmptyCart()
{
  $("input[name=totalCart]").val($("input[name=totalCart]").val()-1);
  if($("input[name=totalCart]").val() >= 1)
  {
    document.getElementById('iframe').contentWindow.location.reload();
  }
  else
  {
    $('#outer-cart').empty();
    $('#outer-cart').append("<div class='container' style='margin-top:2%;'><div class='row'><img class='img-responsive col-xs-12' src='/imageLocation/Img/empty_cart.png' alt='><div class='col-xs-12'><div></div></div></div></div>");
  }
  var count = parseInt($("#cart").children(".badge").html()) -1;
  if(count >= 1)
  {
    $("#cart").children(".badge").html(count);
  }
  else
  {
    $("#cart").children(".badge").remove();
  }
}

$('.delete').on('click',function(event){
    if(confirm('Are you sure you want to remove from cart?')){
        cart = $(this).parentsUntil('.cart');
        cid = cart.children("input[name=id]").val();
        $('.delete').attr("disabled",true);
        $.ajax({
          url: 'index.php?r=cart/delete',
          type: 'GET',
          dataType: 'json',
          data: {id: cid},
        })
        .done(function(data) {
            if(data === 1){
              cart.remove();
              detectEmptyCart();
              $('.delete').attr("disabled",false);
            }
            else{
              alert("Delete Fail!");
              $('.delete').attr("disabled",false);
            }
        })
        .fail(function(e) {
          console.log(e);
          $('.delete').attr("disabled",false);
        })
    }
});

$('footer.content').on('click', '.plusMinus', function(event) {
  event.preventDefault();
  $(this).attr("disabled", true);
  cart = $(this).parentsUntil('.cart');
  parent = $(this).parent();
  cid = cart.children("input[name=id]").val();
  plusMinus = $(this).text();
  value = plusMinus == '+' ? 'plus' : 'minus';
  quantity(value,cid)
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
 /* alert(document.getElementById("subtotal").innerHTML);*/
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
        console.log(obj.code);
       // document.getElementById("discount").style = "display:block;";
		discout = (parseFloat(obj['discount'])).toFixed(2);
        document.getElementById("early").innerHTML = ""+(0).toFixed(2);
        document.getElementById("subtotal").innerHTML = (parseFloat(obj['sub'])).toFixed(2);
        document.getElementById("delivery").innerHTML = (parseFloat(obj['deli'])).toFixed(2);
        document.getElementById("total").innerHTML = (obj['total']).toFixed(2);
        $("input[name='code']").val(obj.code);
        document.getElementById("voucher").style ='display:none';
        document.getElementById("refresh").style ='display:block';
        document.getElementById("discount-tr").style = "display:none";

		$('.table-total tr:nth-child(2)').after("<tr><td><b>Discount</b></td><td class='text-xs-left'>-RM "+discout+"</td></tr>");
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

    document.getElementById("dis").style = "display:block";

  }


  function refresh()
  {
    location.reload();
  }
