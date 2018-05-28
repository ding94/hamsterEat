
 

  

function halalstatus(type,url,halal){
  var x = window.matchMedia("(max-width: 767px)")
  x.addListener(spinnerView)
  function spinnerView(x) {
      if (x.matches) { // If media query matches
            $('.halal-ph-spin').show();  
      }else{
             $('.halal-spin').show();
      }
  }
  if(type != halal){
    
    // Call listener function at run time
    spinnerView(x)

    $.ajax({
          url :url,
          type: "post",
          data :{
              type :type,
          },
        

          success: function (data) {
            location.reload();
            //closeNew();
          },
          error: function (request, status, error) {
            console.log(request.responseText);
          }

    });
  };

}


