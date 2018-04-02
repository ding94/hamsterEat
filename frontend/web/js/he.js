
$(document).ready(function() {
  value = $("input[name='cookie']").val();
  
  if(value == 1)
  {
      $("#type-modal").modal('show');
  }
});

$(".halal").children('a').click(function(){
  passType(1,$(this).attr('data-url'));
});

$(".non-halal").children('a').click(function(){
  passType(0,$(this).attr('data-url'));
});


function passType(type,url){
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

function closeBanner(){
  var promoBanner = document.getElementById('promo-banner');
  var promoBannerEmptyDiv = document.getElementById('promo-banner-empty-div');
  promoBanner.style.display = "none";
  promoBannerEmptyDiv.style.display = "none";
}


/*if($( window ).width() > 767)
{
  var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
  (function(){
  var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
  s1.async=true;
  s1.src='https://embed.tawk.to/5ab860194b401e45400e0a00/1c9g3hsq7';
  s1.charset='UTF-8';
  s1.setAttribute('crossorigin','*');
  s0.parentNode.insertBefore(s1,s0);
  })();
}*/
 






