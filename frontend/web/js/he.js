
$(document).ready(function() {
  value = $("input[name='cookie']").val();
  
  if(value == 1)
  {
      $("#type-modal").modal('show');
  }
});

$(".halal").children('a').click(function(){
  passType(1,$(this).attr('data-url'));
})

$(".non-halal").children('a').click(function(){
  passType(0,$(this).attr('data-url'));
})

function passType(type,url){
  console.log(url);
  $.ajax({
      url :url,
      type: "post",
      data :{
        type :type,
    },
    success: function (data) {
      $("#type-modal").modal('hide');
    },
    error: function (request, status, error) {
      //console.log(request.responseText);
    }

  });
}

// function closeBanner(){
//   var promoBanner = document.getElementById('promo-banner');
//   promoBanner.style.display = "none";
// }
