
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

  value = $("input[name='cookie']").val();
  
  if(value == 1)
  {
      $("#type-modal").modal('show');
  }
  
});

//effect for text changing
var cnt=0, texts=[];
var $fclick = false;


$(".imawhat").each(function() {
  texts[cnt++]=$(this).text();
});

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

$(".halal").children('a').click(function(){
   passType(1);
})

$(".non-halal").children('a').click(function(){
  passType(0);
})

function passType(type){
  $.ajax({
      url :"index.php?r=site/selectiontype",
      type: "post",
      data :{
        type :type,
    },
    success: function (data) {
      $("#type-modal").modal('hide');
    },
    error: function (request, status, error) {
      //alert(request.responseText);
    }

  });
}