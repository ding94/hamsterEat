$('body').on('submit','#a2cart',function(e){
	e.preventDefault();
	e.stopImmediatePropagation();
	$('.addtocart-btn').attr("disabled", true);
	var form = $(this);
  
	$.ajax({
			async: true,
            url    : $("input[name=url]").val(),
            type   : 'post',
            data   : form.serialize(),
            success: function (data) 
            {  
            	obj = JSON.parse(data);
              
            	if(obj.value == 1 || obj.value == 4)
           		{
           			$('#system-messages').append("<div id='aa' class='alert alert-success'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"+obj.message+"</div>").fadeIn();
	           		//$('#system-messages').children().delay(3000).fadeTo(500,0).slideUp(500).queue(function() { $('#aa').remove(); });
           			//$("#w1-success-0").html(data).fadeIn().delay(3000).fadeOut();
           			$("#foodDetail").modal('hide');
                if(obj.value == 1)
                {
                    if(!($(".cart").children(".badge").html()))
                  {
                    $(".cart").children('.badge').html(1);
                  }
                  else
                  {
                    var count = parseInt($(".cart").children(".badge").html()) +1;
                    $(".cart").children(".badge").html(count);
                  }
                }
           			//location.reload();
           		}
           		else
	           	{
	           		$('#system-messages').append("<div id='aa' class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"+obj.message+"</div>").fadeIn();
	           		//$('#system-messages').children().delay(3000).fadeTo(500,0).slideUp(500).queue(function() { $('#aa').remove(); });
           			//$('#system-messages').html(data).fadeIn();
                console.log(obj.message);
           		} 
           		$('.addtocart-btn').attr("disabled", false);   
            },
            error  : function (e) 
            {
                console.log(e);
            }
       });
})

/* JS function to calculate and display food price with user selection in add to cart button */
function calcprice() {
  price = 0;
  var foodprice = parseFloat($(".foodprice").data("price"));
  var quantity = document.getElementById("cart-quantity").value;
  $(".price:checked").each(function () {
    price += parseFloat($(this).next().children("span.selection-price").data("price"));
  });
  var priceperunit = (price + foodprice);
  var totalprice = quantity*priceperunit;
  if ($(".price:checked")['length']==0){
    $(".total-price").html((foodprice*quantity).toFixed(2));
  }else{
  $(".total-price").html(totalprice.toFixed(2));
  }
}

$(document).ready(function () {
  $("body").change(function () {
    calcprice()
  });
});