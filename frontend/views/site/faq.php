<?php $this->title = "Guide"; ?>

<div class = "container guide">
    <ul class="nav nav-pills ul-center">
        <li class="active"><a data-toggle="pill" href="#guide"><h4>Guide</h4></a></li>
        <li><a data-toggle="pill" href="#faq"><h4>FAQ</h4></a></li>
    </ul>

    <div class="tab-content">
        <div id="guide" class="tab-pane fade in active">
            <ul class="nav nav-pills ul-center">
                <li class="active"><a data-toggle="pill" href="#member"><h4>General</h4></a></li>
                <li><a data-toggle="pill" href="#restaurantmanager"><h4>Restaurant Manager</h4></a></li>
                <li><a data-toggle="pill" href="#deliveryman"><h4>Delivery Man</h4></a></li>
            </ul>

            <div class="tab-content">
                <div id="member" class="tab-pane fade in active">
                    <h1><strong>General</strong></h1>
                    <br>
                    <p><strong>HamsterEat</strong></p>
                    <p align = "justify">HamsterEat is a platform where we enable our users to have a wide range of food delivered to their doorstep. Users that wish to enjoy our features would have to sign up to be a member 
                    first. There are 3 types of membership that users could choose to sign up as - Member, Restaurant Manager and Delivery Man. These 3 parts play a very crucial role in HamsterEat.</p>
                    <table class= table table-user-info>
                        <tr>
                            <th> Member </th>
                            <td> Members are the customers of HamsterEat who will be ordering food from restaurants on HamsterEat. </td>
                        </tr>
                        <tr>
                            <th> Restaurant Manager </th>
                            <td> Restaurant Managers will be responsible for setting up restaurant pages on HamsterEat which will allow them to sell food and beverages of any type. </td>
                        </tr>
                        <tr>
                            <th> Delivery Man </th>
                            <td> Delivery Men will be the one who delivers your food to your designated location ensuring its still warm when you receive it. </td>
                        </tr>
                    </table>
                    <br>
                    <br>
                    <br>
                    <p><strong>Placing an Order</strong></p>
                    <p align = "justify">Before you are able to place an order, you have to log in if you already own an account or sign up if you do not have one. You can place an order by selecting your area, then proceeding 
                    to add the available items in that specific area to your cart. Once done, you can proceed to your cart and check out. Take note that items from a different area cannot be added to your 
                    cart unless you change your area.</p>
                    <br>
                    <br>
                    <p><strong>Payment</strong></p>
                    <p align = "justify">There are currently only 2 payment options available.</p>
                    <table class= table table-user-info>
                        <tr>
                            <th> Account Balance </th>
                            <td> Users must top up via offline top up in order to use this option for payment. Currently the only bank available for top up is Maybank. Once top up is successful the balance will be 
                            in your account until you spent it. </td>
                        </tr>
                        <tr>
                            <th> Cash on Delivery </th>
                            <td> Payment must be made when the delivery men delivers your food to you. Exact amount is recommended as sometimes our delivery man may not have small change. </td>
                        </tr>
                    </table>
                    <br>
                    <br>
                    <br>
                    <p><strong>Account Upgrade</strong></p>
                    <p align = "justify">Users that first sign up to be a member can upgrade their account to either a Restaurant Manager or a Delivery Man. In order to upgrade your account, we will need a few personal 
                    details. After applying, users must wait for 1-2 working days for our staff to approve or reject the upgrade. If the upgrade is approved the user is to take responsibility for the work that has to be 
                    done which is stated in our Terms and Conditions shwon to the user before the user applies for an upgrade. Take note that account upgrades are irreversible or changeable.</p>
                </div>

                <div id="restaurantmanager" class="tab-pane fade">
                    <h1><strong>Restaurant Manager</strong></h1>
                    <br>
                    <p><strong>Incoming Orders</strong></p>
                    <p align = "justify">You will receive a notification every time your restaurant receives an order. Every order you receive must be accepted unless you provide a valid reason to us, should a number of orders 
                    be neglected, action will be taken against you. Item preparation status <strong>must</strong> be updated accordingly.</p>
                    <br>
                    <br>
                    <p><strong>Setting Up a Restaurant</strong></p>
                    <p align = "justify">Simply create a restaurant by providing your restaurant's basic details and after that uploading your restaurant's menu to complete your restaurant. Take note that the area plays 
                    an important part and it will only be shown to customers in that area.</p>
                    <br>
                    <br>
                    <p><strong>Sold Out / Unavailable Item</strong></p>
                    <p align = "justify">If your item is currently out of stock or unavailable due to unforseen circumstances, you may temporarily "delete" that item which will move that item into the recycle bin. 
                    Items in the recycle bin may be restored anytime.</p>
                    <br>
                    <br>
                    <p><strong>Close Restaurant</strong></p>
                    <p align = "justify">If your restaurant isn't open for the day, you can close your restaurant to prevent customers from ordering from your restaurant for the day. Kindly remember to open it back the day after.</p>
                </div>

                <div id="deliveryman" class="tab-pane fade">
                    <h1><strong>Delivery Man</strong></h1>
                    <br>
                    <p><strong>Daily Sign In</strong></p>
                    <p align = "justify">In order to be assigned deliveries, you are required to click on the "Sign In" button provided in the Daily Sign In for Delivery Man to prove that you are active and willing to deliver for today. 
                    You will not be receiving any deliveries without pressing the "Sign In" button.</p>
                    <br>
                    <br>
                    <p><strong>Incoming Deliveries</strong></p>
                    <p align = "justify">You will receive a notification every time you are assigned an order. Orders assigned to you will be in the same area as your provided address. Every order you receive must be accepted unless you provide a valid reason to us, should a number of orders 
                    be neglected, action will be taken against you. Delivery status <strong>must</strong> be updated accordingly.</p>
                    <br>
                    <br>


                </div>
            </div>
        </div>

        <div id="faq" class="tab-pane fade">
            <h1><strong>Frequently Asked Questions</strong></h1>
            <br>
            <ul>
                <li><strong>Q:</strong> What if I encounter a problem with my order?</li>
                <li><strong>A:</strong> Kindly contact our customer service and provide your Delivery ID before describing your problem.</li>
                <br>
                <li><strong>Q:</strong> My postcode is not listed. What should I do?</li>
                <li><strong>A:</strong> Our services are currently expanding. Do wait until your area is available.</li>
                <br>
                <li><strong>Q:</strong> Can I cancel an order that I have already placed?</li>
                <li><strong>A:</strong> No, you cannot cancel an order that is successfully placed.</li>
                <br>
                <li><strong>Q:</strong> Where can I see my ongoing orders or my completed orders?</li>
                <li><strong>A:</strong> You can see your ongoing orders under <strong>My Profile > My Orders</strong> and your completed orders under <strong>My profile > Order History</strong>.</li>
                <br>
                <li><strong>Q:</strong> Can I withdraw my account balance?</li>
                <li><strong>A:</strong> Yes, you may withdraw the balance left in your account, but we will be deducting RM 2.00 as procedure fee.</li>
                <br>
                <li><strong>Q:</strong> Can I upgrade my account to a Restaurant Manager or a Delivery Man from a member account?</li>
                <li><strong>A:</strong> Yes, you may upgrade your account to either a Restaurant Manager or a Delivery Man but you may not be both of that. A Restaurant Manager or Delivery Man account has the features of a member account.</li>
                <br>
                <li><strong>Q:</strong> Can i downgrade my account from a Delivery Man / Restaurant Manager to a member account?</li>
                <li><strong>A:</strong> No, you cannot downgrade your account.</li>
                <br>
                <li><strong>Q:</strong> Why can't I place my order before 9:30am and after 11:00am daily?</li>
                <li><strong>A:</strong> We are currently still in our beta phase, thus we only offer lunch deliveries for now.</li>
                <br>
                <li><strong>Q:</strong> What if I want to avoid certain ingredients in my order due to allergies etc.?</li>
                <li><strong>A:</strong> Kindly write down what you would like to avoid in the remarks section provided.</li>
                <br>
                <li><strong>Q:</strong> Is there a minimum fee I have to hit in order to place an order?</li>
                <li><strong>A:</strong> Yes, your order must be at least RM 10.00 excluding delivery charge in order to place your order.</li>
                <br>
                <li><strong>Q:</strong> How do I use the voucher or discount codes I received?</li>
                <li><strong>A:</strong> You can use the codes before you check out in the voucher / discount code field provided.</li>
                <br>
                <li><strong>Q:</strong> How do I invite a friend to join HamsterEat?</li>
                <li><strong>A:</strong> You can provide your friend your referral link which he can use to sign up an account and get a RM 5.00 voucher which the referrer will get as well.</li>
                <br>
                <li><strong>Q:</strong> How does HamsterEat differentiate areas?</li>
                <li><strong>A:</strong> We mainly differentiate areas based on postcodes. However, under certain circumstances where the postcode areas are too big, we will divide it into smaller parts.</li>
                <br>
                <li><strong>Q:</strong> Do I have to tip my delivery man?</li>
                <li><strong>A:</strong> Tipping is not necessary but it is up to the customer's own free will.</li>
            </ul>
        </div>
    </div>
</div>