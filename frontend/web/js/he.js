
$(document).ready(function() {
	setInterval(function() {
  $('.element1').addClass('bounce');
	window.setTimeout(function(){
        $('.element2').addClass('bounce');
    }, 1500);
    window.setTimeout(function(){
        $('.element3').addClass('bounce');
    }, 3000);
    window.setTimeout(function(){
        $('.element1').removeClass('bounce');
        $('.element2').removeClass('bounce');
        $('.element3').removeClass('bounce');
    }, 5000);
}, 7000);

    $("#myModal").modal('show');
  
});

//effect for text changing
var cnt=0, texts=[];
var $fclick = false;


$(".imawhat").each(function() {
  texts[cnt++]=$(this).text();
});

function changePrice(){
    price =$('#afterprice').val();
    $('#price').val((price/1.3).toFixed(2));
}

function beforeMarkUp()
{
    $('.selectionTable').children('tbody').each(function(){
        trChild = $(this).children('tr');
        trChild.each(function(){
            value = $(this).children('.selectionPrice').children('.form-group').children('input').val();
            $(this).children('.selectionBefore').children('.form-group').children('input').val((value/1.3).toFixed(2));
        })
    });
}


function fadeText() {
  if (cnt>=texts.length) { cnt=0; }
  $('.ima').html(texts[cnt++]);
  $('.ima')
    .fadeIn('fast').animate({opacity: 1.0}, 5000).fadeOut('fast',
     function() {
       return fadeText();
     }
  );
}


fadeText();