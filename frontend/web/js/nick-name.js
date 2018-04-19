$('body').on('submit','#a2Nick',function(e){
	e.preventDefault();
	e.stopImmediatePropagation();
	
	$.ajax({
		url: $("input[name=convert-url]").val(),
		type: 'POST',
		data: $(this).serialize(),
	})
	.done(function(data) {
		obj = JSON.parse(data);
		if(obj.value == 1 || obj.value == 2	)
		{
			$('#system-messages').append("<div id='aa' class='alert alert-success'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"+obj.message+"</div>").fadeIn();
            $('#system-messages').children().delay(3000).fadeTo(500,0).slideUp(500).queue(function() { $('#aa').remove(); });
            $("#orderQuantity").modal('hide');
		}
		else
		{
			$('#system-messages').append("<div id='aa' class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"+obj.message+"</div>").fadeIn();
		}
	})
	.fail(function(e) {
		console.log(e);
	})
	
})

$(".skip-name").on('click', function(event) {
	event.preventDefault();
	$("#orderQuantity").modal('hide');
});