$('body').on('submit','#a2cart',function(e){
	e.preventDefault();
	e.stopImmediatePropagation();
	var form = $(this);
	id = $(this).children("input[name='id']").val();
	
	$.ajax({
			async: true,
            url    : 'index.php?r=cart/addto-cart&id='+id,
            type   : 'POST',
            data   : form.serialize(),
            success: function (data) 
            {  
            	if(data == 1)
           		{
           			//$("#w1-success-0").html(data).fadeIn().delay(3000).fadeOut();
           			location.reload();
           		}
           		else
	           	{
	           		$('#system-messages').append("<div id='aa' class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"+data+"</div>").fadeIn();
	           		$('#system-messages').children().delay(3000).fadeTo(500,0).slideUp(500).queue(function() { $('#aa').remove(); });
           			//$('#system-messages').html(data).fadeIn();
           		}      
            },
            error  : function (e) 
            {
                console.log(e);
            }
       });
})

