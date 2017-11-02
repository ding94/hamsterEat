function checkempty()
    {
        if (document.getElementsByName("Orders[Orders_PaymentMethod]")[1].checked) 
        {
            var aler = "Are you sure to pay with Account Balance?";
        }
        else if (document.getElementsByName("Orders[Orders_PaymentMethod]")[2].checked)
        {
            var aler = "Are you sure to pay cash on delivery?";
        }
        else
        {
            alert('Please select a payment method!');
            document.getElementById("list").style.color ="red";
            return false;
        }

        var con = confirm(aler);

        if (con == true) 
        {
            if (document.getElementById("orders-orders_location").value && document.getElementById("orders-orders_area").value)
            {
                return true;
            }
            else
            {
                alert('Address has no completed yet!');
                return false;
            }
        }
        return false;
    }