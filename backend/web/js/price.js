$(".orgin-price").on('change', 'input', function(event) {
	event.preventDefault();
	price = $(this).val() * 1.3;
	$(".markup-price").find('input').val(price.toFixed(1));
});

$(".markup-price").on('change', 'input', function(event) {
	event.preventDefault();
	price = $(this).val() / 1.3;
	$(".orgin-price").find('input').val(price.toFixed(1));
});