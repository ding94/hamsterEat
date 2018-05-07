$("#phone-validate").on('click', function(event) {
	event.preventDefault();
	/* Act on the event */
	url = $("input[name=url]").val();
	phone_number = $("#companysignupform-contact_number").val();

	if(phone_number !== "")
	{
		$.ajax({
			url: url,
			type: 'POST',
			data: {phone_number: phone_number},
		})
		.done(function(data) {
			obj = JSON.parse(data);
			if(obj.value == 0)
			{
				$('#system-messages').append("<div id='aa' class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"+obj.message+"</div>").fadeIn();
			}
			else
			{
				$('#system-messages').append("<div id='aa' class='alert alert-success'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"+obj.message+"</div>").fadeIn();
			}
		})
		.fail(function() {
			console.log("error");
		})
	}
});

$("#signup-phone-validate").on('click', function(event) {
	event.preventDefault();
	/* Act on the event */
	url = $("input[name=url]").val();
	phone_number = $("#userdetails-user_contactno").val();
	if(phone_number !== "")
	{
		$.ajax({
			url: url,
			type: 'POST',
			data: {phone_number: phone_number},
		})
		.done(function(data) {
			obj = JSON.parse(data);
			if(obj.value == 0)
			{
				$('#system-messages').append("<div id='aa' class='alert alert-danger'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"+obj.message+"</div>").fadeIn();
			}
			else
			{
				$('#system-messages').append("<div id='aa' class='alert alert-success'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"+obj.message+"</div>").fadeIn();
			}
		})
		.fail(function() {
			console.log("error");
		})
	}
});