function proceed(){
    var form = document.getElementById("home");
    var inputs = form.getElementsByTagName("input"), input = null, not_pass = false;
    for(var i = 0, len = inputs.length; i < len; i++) {
        input = inputs[i];

        if(input.type == "hidden") {
            continue;
        }

        if(input.type == "radio" && !input.checked) {
            not_pass = true;
        } 
        if(input.type == "radio" && input.checked){
            not_pass = false;
            break;
        }

        if(input.text == "text") {
          continue;
        }
    }

    if (not_pass) {
        // $("#req-message").show();//this div # in your form
        window.alert("Please select all required fields!");
        return false;
    } else {
      $('#home').removeClass("in active");
      $('#comments').addClass("in active");
    }
}