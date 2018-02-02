$(function(){
   $('#user-role').on('change', function(event) {
   	event.preventDefault();
   	/* Act on the event */
   	var value = this.value;
   	var word = value.split(' ').join('-');
  
   	$("."+word).removeClass('none');
   	if(word == 'rider')
   	{
   		$(".restaurant-manager").addClass('none');
   	}

   	if(word == "restaurant-manager")
   	{
   		$(".rider").addClass('none');
   	}
   });
});