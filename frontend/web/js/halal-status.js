function halalstatus(type,url){
  
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
}


