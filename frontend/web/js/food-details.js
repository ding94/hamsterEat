$('body').on('submit','#a2cart',function(e){
	e.preventDefault();
	e.stopImmediatePropagation();
	$('.addtocart-btn').attr("disabled", true);
	var form = $(this);
	id = $(this).children("input[name='id']").val();
	$.ajax({
			async: true,
            url    : 'index.php?r=cart/addto-cart&id='+id,
            type   : 'POST',
            data   : form.serialize(),
            success: function (data) 
            {  
            	obj = JSON.parse(data);
            	
            	if(obj.value == 1)
           		{
           			$('#system-messages').append("<div id='aa' class='alert alert-success'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"+obj.message+"</div>").fadeIn();
	           		$('#system-messages').children().delay(3000).fadeTo(500,0).slideUp(500).queue(function() { $('#aa').remove(); });
           			//$("#w1-success-0").html(data).fadeIn().delay(3000).fadeOut();
           			$("#foodDetail").modal('hide');
           			if(!($("#cart").children(".badge").html()))
           			{
           				$("#cart").children('.badge').html(1);
           			}
           			else
           			{
           				var count = parseInt($("#cart").children(".badge").html()) +1;
           				$("#cart").children(".badge").html(count);
           			}
           			//location.reload();
           		}
           		else
	           	{
	           		$('#system-messages').append("<div id='aa' class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"+obj.message+"</div>").fadeIn();
	           		$('#system-messages').children().delay(3000).fadeTo(500,0).slideUp(500).queue(function() { $('#aa').remove(); });
           			//$('#system-messages').html(data).fadeIn();
           		} 
           		$('.addtocart-btn').attr("disabled", false);   
            },
            error  : function (e) 
            {
                console.log(e);
            }
       });
})