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
    $('#outer-cart').append("<div class='container' style='margin-top:2%;'><div class='row'><div class='col-xs-12'><div><img class='img-responsive col-xs-12' src='../web/imageLocation/Img/empty_cart.png'></div></div></div></div>");
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
       
        url = $(this).attr('data-url');
        id = $(this).attr('data-id');
        cart = $("#cart-"+id);
        
        cid = $("."+id+"-id").val();
        console.log(cid);
        $('.delete').attr("disabled",true);
        $.ajax({
          url: url,
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
  url = $(this).attr('data-url');
  
  $(this).attr("disabled", true);
  id = $(this).attr('data-id');
  parent = $(this).parent();
  cid = $("."+id+"-id").val();
  plusMinus = $(this).text();
  value = plusMinus == '+' ? 'plus' : 'minus';
  quantity(value,cid,url)
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


function quantity(up,cid,url)
{
  return $.ajax({
    url: url,
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
    url : $("input[name=dis-url]").val(),
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
        document.getElementById("early").innerHTML = ""+(0).toFixed(2);
        document.getElementById("subtotal").innerHTML = (parseFloat(obj['sub'])).toFixed(2);
        document.getElementById("delivery").innerHTML = (parseFloat(obj['deli'])).toFixed(2);
        document.getElementById("total").innerHTML = (obj['total']).toFixed(2);
        $("input[name='code']",parent.document).val(obj['code'].replace(/\s+/g,''));

        document.getElementById("voucher").style ='display:none';
        document.getElementById("earlytd").style ='display:none';
        document.getElementById("cs").style ='display:none';
        document.getElementById("pcs").style ='display:none';
        document.getElementById("refresh").style ='display:block';
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

  function showDiv() {
   document.getElementById('cs').style.display = "block";
   document.getElementById('pc').innerHTML = "Enter Promo Code:";
   document.getElementById('refresh').style.display = "block";
   document.getElementById('voucher').style.display = "none";
  }


// Add no-touch class to body for mobile touch events and toggle hover class on elements that need it
 if ("ontouchstart" in document.documentElement) {
    document.documentElement.className += " touch";
  }
  
  // Add and remove no-hover class to <li>'s for mobile hover events
  $('.touch .relative').each(function() {
    var div = jQuery(this);
    
    div.hover(function() {
      div.removeClass('no-hover');
    });
    
    jQuery('*').not(div).bind('click', function() {
      div.addClass('no-hover');
    });
    
  });