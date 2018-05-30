
$(document).ready(function() {
  value = $("input[name='cookie']").val();
  
  if(value == 1)
  {
      $("#type-modal").modal('show');
  }
  else
  {
     closeNew();
  }
  user_valid = $("input[name='user-validation']").val()
  if (user_valid == 10) {
    detectPayment();
    detectPendingPayment();
  }
});

$(".halal").children('a').click(function(){
  passType(1,$(this).attr('data-url'),$(this).attr('refresh'));
});

$(".non-halal").children('a').click(function(){
  passType(0,$(this).attr('data-url'),$(this).attr('refresh'));
});


function passType(type,url,refresh){
  $.ajax({
      url :url,
      type: "post",
      data :{
        type :type,
        refresh:refresh,
    },  
    success: function (data) {
      $("#type-modal").modal('hide');
      if(refresh==1){
        console.log(data);
         location.reload();
      }
      //closeNew();
    },
    error: function (request, status, error) {
      console.log(request.responseText);
    }

  });
}

function closeBanner(){
  $.ajax({
    url: document.getElementById('closebanner-link').value,
    type: 'get',
    success: function(data){
      var obj = JSON.parse(data);
    },
    error: function (request, status, error) {
      //console.log(request.responseText);
    }
  });
  var promoBanner = document.getElementById('promo-banner');
  var promoBannerEmptyDiv = document.getElementById('promo-banner-empty-div');
  promoBanner.style.display = "none";
  promoBannerEmptyDiv.style.display = "none";
}

$(function(){
  $('#newsModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var modal = $(this);
    var href = button.attr('href');
    modal.find('.modal-body').html('<center><i class=\"fa fa-spinner fa-spin fa-3x\" style="padding:100px"></i><center>');
    $.post({url : href, async: true, backdropLimit: 1})
        .done(function( data ) {
        modal.find('.modal-body').html(data);
      });
  });
})

function closeNew()
{
  if($("input[name=news]").val() == 1)
  {
    url = $("input[name=news-modal-url]").val();
   
    $('#newsModal').modal('show').find('.modal-body').html('<center><i class=\"fa fa-spinner fa-spin fa-3x\" style="padding:100px"></i><center>');
    $.post({url : url, async: true, backdropLimit: 1})
        .done(function( data ) {
        $('#newsModal').find('.modal-body').html(data);
    });
       
    $.ajax({
      url: $("input[name=news-close-url]").val(),
      type: 'GET',
    })
    .done(function() {
      console.log("success");
    })
    .fail(function(e) {
      console.log(e);
    })
  }
}

function detectPayment()
{
  $.ajax({
    url: $("input[name=detect-payment-url]").val(),
    type: 'GET',
    
  })
  .done(function(obj) {
      data = JSON.parse(obj);
    
      if(data.value === 2 )
      {
          alertPayment(data.link);
      }
      else if(data.value == 1)
      {
        $('#system-messages').append("<div id='aa' class='alert alert-success'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"+obj.link+"</div>").fadeIn();
      }
  })
  .fail(function(e) {
    console.log(e);
  });
  
}

function detectPendingPayment(){
   $.ajax({
    url: $("input[name=detect-pending-payment-url]").val(),
    type: 'POST',
    data:{
      action : $("input[name=current-url]").val(),
    },
  })
  .done(function(obj) {
      data = JSON.parse(obj);
      if (data['valid']==1) {
        if (confirm('Do You Want To Continue To Proceed Your payment')) {
           window.location.href = data['url'];
        }
      }
  })
  .fail(function(e) {
    console.log(e);
  });
}

function alertPayment(link)
{
  if (confirm('Do You Want To Continue To Proceed Your payment')) {
     window.location.href = link;
  } else {
    console.log($("input[name=close-payment-url]").val());
    $.ajax({
      url: $("input[name=close-payment-url]").val(),
    })
    .done(function(data) {
      console.log(data);
    })
    .fail(function(e) {
      console.log(e);
    });
  }
 
}


if($( window ).width() > 767)
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
}







