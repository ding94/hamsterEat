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
    $(".cart").children(".badge").html(count);
  }
  else
  {
    $(".cart").children(".badge").remove();
  }
}

$('.new-nick').on('click', function(event) {
  event.preventDefault();
  /* Act on the event */
  button = $(this);
  button.attr("disabled",true);
  parent = $(this).parent('.panel-body');
  id = $(this).attr('data-id');
  url = $("input[name=add-nick-url]").val();
  length = parent.children('div.input-group').length+1;
 
  $.ajax({
    url: url,
    type: 'GET',
    data: {id: id,length:length},
  })
  .done(function(data) {
    obj = JSON.parse(data);
    if(obj.value == 1)
    {
      parent.append(obj.message);
    }
    else
    {
      $('#system-messages').append("<div id='aa' class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"+obj.message+"</div>").fadeIn();
    }
  })
  .fail(function(e) {
    console.log(e);
  });
   button.attr("disabled",false);
});

$(".panel-body").on('click','a.delete-nick' ,function(event) {
  event.preventDefault();
  button = $(this);
  button.attr("disabled",true);
  /* Act on the event */
  divdelete = $(this).closest('div.input-group').next();
  brdelete = $(this).closest('div.input-group');
  if(typeof($(this).attr('attr-id'))  ===  "undefined")
  {
    divdelete.remove();
    brdelete.remove();
  }
  else
  {
    $.ajax({
      url: $("input[name=remove-nick-url]").val(),
      type: 'GET',
      data: {id: $(this).attr('attr-id')},
    })
    .done(function(data) {

      obj = JSON.parse(data);
      if(obj.value == 1)
      {
        $('#system-messages').append("<div id='aa' class='alert alert-success'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"+obj.message+"</div>").fadeIn();
        $('#system-messages').children().delay(3000).fadeTo(500,0).slideUp(500).queue(function() { $('#aa').remove(); });
        divdelete.remove();
        brdelete.remove();
      }
      else
      {
        $('#system-messages').append("<div id='aa' class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"+obj.message+"</div>").fadeIn();
      }
    })
    .fail(function(e) {
      console.log(e);
    })
  }
  button.attr("disabled",false);
});

$(".panel-body").on('keypress', function(event) {
  return event.which !== 13;
  /* Act on the event */
});

$(".panel-body").on('change propertychange', '.nick-edit', function(event) {
  event.preventDefault();
  /* Act on the event */
  text = $(this);
  arrayID = text.attr('data-id');
  id = JSON.parse(arrayID);
  url = $("input[name=update-nick-url]").val(),
  $.ajax({
    url: url,
    type: 'POST',
    data: {
      id: id.id,
      cid : id.cid,
      name : text.val(),
    },
  })
  .done(function(data) {
    obj = JSON.parse(data);
    if(obj.value == 1)
    {
      text.attr('data-id',obj.message);
    }
    else
    {
       $('#system-messages').append("<div id='aa' class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"+obj.message+"</div>").fadeIn();
    }
  })
  .fail(function(e) {
    console.log(e);
  })
});

$('.delete').on('click',function(event){
    if(confirm('Are you sure you want to remove from cart?')){
       
        url = $(this).attr('data-url');
        id = $(this).attr('data-id');
        cart = $("#cart-"+id);
        
        cid = $("."+id+"-id").val();
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
          console.log(e.responseText);
          $('.delete').attr("disabled",false);
        })
    }
});

$('.footer-content-container').on('click', '.plusMinus', function(event) {
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
    //alert(document.getElementById("codes").value);
    console.log(document.getElementById("codes").value.replace(/\s+/g, ''));
    console.log(parseFloat(document.getElementById("subtotal").innerHTML).toFixed(2));
    console.log(parseFloat(document.getElementById("delivery").innerHTML).toFixed(2));
    console.log(parseFloat(document.getElementById("total").innerHTML).toFixed(2));
    $.ajax({
    url : $("input[name=dis-url]").val(),
    type: "get",
    data :{

      dis: document.getElementById("discountitem-description").value.replace(/\s+/g, ''),
      codes: document.getElementById("codes").value.replace(/\s+/g, ''),

      sub: parseFloat(document.getElementById("subtotal").innerHTML).toFixed(2),
      deli: parseFloat(document.getElementById("delivery").innerHTML).toFixed(2),
      total: parseFloat(document.getElementById("total").innerHTML).toFixed(2),
    },
    success: function (data) {
      var obj = JSON.parse(data);
      //alert(obj);
      if (obj['error'] != 1 ) 
      {
        //document.getElementById("disamount").innerHTML = "- "+(parseFloat(obj['discount'])).toFixed(2);
        
       // document.getElementById("discount").style = "display:block;";
        discout = (parseFloat(obj['discount'])).toFixed(2);
        if(document.getElementById("early") != null)
        {
           document.getElementById("early").innerHTML = ""+(0).toFixed(2);
            document.getElementById("earlytd").style ='display:none';
        }
        console.log(obj);
        document.getElementById("subtotal").innerHTML = (parseFloat(obj['sub'])).toFixed(2);
        document.getElementById("delivery").innerHTML = (parseFloat(obj['deli'])).toFixed(2);
        document.getElementById("total").innerHTML = (obj['total']).toFixed(2);
        $("input[name='code']",parent.document).val(obj['code'].replace(/\s+/g,''));

        document.getElementById("voucher").style ='display:none';
       
        document.getElementById("cs").style ='display:none';
        document.getElementById("pcs").style ='display:none';
        document.getElementById("refresh").style ='display:block';
    $('.table-total tr:nth-child(2)').after("<tr><td><b>Discount</b></td><td class='text-xs-left'>-RM "+discout+"</td></tr>");
      }
      else
      {
        if (obj['item'] == 1){
          alert("Coupon was expired! Please check your account > Discount Codes");
          return false;
        }
        else if(obj['item'] == 2){
          if (obj['condition'] == 1) {
            alert('You already used this coupon.'); 
            return false;
          }
          else if (obj['condition'] == 2) {
            alert('You need to fulfill purchase amount. RM'+obj['amount']); 
            return false;
          }
        }
        else{
          alert("No coupon found or coupon expired! Please check your account > Discount Codes");
          return false;
        }
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
 function showSearchbox() {
    var x = document.getElementById("search-box");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
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

// js for cart collapse panel chevron arrow display
$('.panel-collapse').on('show.bs.collapse', function () {
  $(this).siblings('.panel-heading').addClass('active');
});

$('.panel-collapse').on('hide.bs.collapse', function () {
  $(this).siblings('.panel-heading').removeClass('active');
});