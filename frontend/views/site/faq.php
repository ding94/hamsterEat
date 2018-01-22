<?php $this->title = "Guide"; ?>

<div class = "container guide">
    <ul class="nav nav-pills ul-center">
        <li class="active"><a data-toggle="pill" href="#guide"><h4><?= Yii::t('site','Guide') ?></h4></a></li>
        <li><a data-toggle="pill" href="#faq"><h4><?= Yii::t('site','FAQ') ?></h4></a></li>
    </ul>

    <div class="tab-content">
        <div id="guide" class="tab-pane fade in active">
            <ul class="nav nav-pills ul-center">
                <li class="active"><a data-toggle="pill" href="#member"><h4><?= Yii::t('site','General') ?></h4></a></li>
                <li><a data-toggle="pill" href="#restaurantmanager"><h4><?= Yii::t('site','Restaurant Manager') ?></h4></a></li>
                <li><a data-toggle="pill" href="#deliveryman"><h4><?= Yii::t('site','Delivery Man') ?></h4></a></li>
            </ul>

            <div class="tab-content">
                <div id="member" class="tab-pane fade in active">
                    <h1><strong><?= Yii::t('site','General') ?></strong></h1>
                    <br>
                    <p><strong>HamsterEat</strong></p>
                    <p align = "justify">HamsterEat is a platform where we enable our users to have a wide range of food delivered to their doorstep. Users that wish to enjoy our features would have to sign up to be a member 
                    first. There are 3 types of membership that users could choose to sign up as - Member, Restaurant Manager and Delivery Man. These 3 parts play a very crucial role in HamsterEat.</p>
                    <?= Yii::t('site','general-1') ?>
                    <table class= table table-user-info>
                        <tr>
                            <th><?= Yii::t('site','Member') ?></th>
                            <td> Members are the customers of HamsterEat who will be ordering food from restaurants on HamsterEat. <?= Yii::t('site','general-1-1') ?></td>
                        </tr>
                        <tr>
                            <th><?= Yii::t('site','Restaurant Manager') ?></th>
                            <td> Restaurant Managers will be responsible for setting up restaurant pages on HamsterEat which will allow them to sell food and beverages of any type. <?= Yii::t('site','general-1-2') ?></td>
                        </tr>
                        <tr>
                            <th> <?= Yii::t('site','Delivery Man') ?></th>
                            <td> Delivery Men will be the one who delivers your food to your designated location ensuring its still warm when you receive it. <?= Yii::t('site','general-1-3') ?></td>
                        </tr>
                    </table>
                    <br>
                    <br>
                    <br>
                    <p><strong><?= Yii::t('site','Placing an Order') ?></strong></p>
                    <p align = "justify">Before you are able to place an order, you have to log in if you already own an account or sign up if you do not have one. You can place an order by selecting your area, then proceeding 
                    to add the available items in that specific area to your cart. Once done, you can proceed to your cart and check out. Take note that items from a different area cannot be added to your cart unless you change your area.<?= Yii::t('site','general-2') ?></p>
                    <br>
                    <br>
                    <p><strong><?= Yii::t('site','Payment') ?></strong></p>
                    <p align = "justify">There are currently only 2 payment options available.<?= Yii::t('faq','general-3') ?></p>
                    <table class= table table-user-info>
                        <tr>
                            <th> <?= Yii::t('site','Account Balance') ?></th>
                            <td> Users must top up via offline top up in order to use this option for payment. Currently the only bank available for top up is Maybank. Once top up is successful the balance will be 
                            in your account until you spent it. <?= Yii::t('site','general-3-1') ?></td>
                        </tr>
                        <tr>
                            <th> Cash on Delivery<?= Yii::t('site','') ?></th>
                            <td> Payment must be made when the delivery men delivers your food to you. Exact amount is recommended as sometimes our delivery man may not have small change. <?= Yii::t('site','general-3-2') ?></td>
                        </tr>
                    </table>
                    <br>
                    <br>
                    <br>
                    <p><strong><?= Yii::t('site','Account Upgrade') ?></strong></p>
                    <p align = "justify">Users that first sign up to be a member can upgrade their account to either a Restaurant Manager or a Delivery Man. In order to upgrade your account, we will need a few personal 
                    details. After applying, users must wait for 1-2 working days for our staff to approve or reject the upgrade. If the upgrade is approved the user is to take responsibility for the work that has to be 
                    done which is stated in our Terms and Conditions shwon to the user before the user applies for an upgrade. Take note that account upgrades are irreversible or changeable.<?= Yii::t('site','general-4') ?></p>
                </div>

                <div id="restaurantmanager" class="tab-pane fade">
                    <h1><strong><?= Yii::t('site','Restaurant Manager') ?></strong></h1>
                    <br>
                    <p><strong><?= Yii::t('site','Incoming Orders') ?></strong></p>
                    <p align = "justify">You will receive a notification every time your restaurant receives an order. Every order you receive must be accepted unless you provide a valid reason to us, should a number of orders 
                    be neglected, action will be taken against you. Item preparation status <strong>must</strong> be updated accordingly.<?= Yii::t('site','rmanager-1') ?></p>
                    <br>
                    <br>
                    <p><strong><?= Yii::t('site','Setting Up a Restaurant') ?></strong></p>
                    <p align = "justify">Simply create a restaurant by providing your restaurant's basic details and after that uploading your restaurant's menu to complete your restaurant. Take note that the area plays 
                    an important part and it will only be shown to customers in that area.<?= Yii::t('site','rmanager-2') ?></p>
                    <br>
                    <br>
                    <p><strong><?= Yii::t('site','Sold Out') ?> / <?= Yii::t('site','Unavailable Item') ?></strong></p>
                    <p align = "justify">If your item is currently out of stock or unavailable due to unforseen circumstances, you may temporarily "delete" that item which will move that item into the recycle bin. 
                    Items in the recycle bin may be restored anytime.<?= Yii::t('site','rmanager-3') ?></p>
                    <br>
                    <br>
                    <p><strong><?= Yii::t('site','Close Restaurant') ?></strong></p>
                    <p align = "justify">If your restaurant isn't open for the day, you can close your restaurant to prevent customers from ordering from your restaurant for the day. Kindly remember to open it back the day after.<?= Yii::t('site','rmanager-4') ?></p>
                </div>

                <div id="deliveryman" class="tab-pane fade">
                    <h1><strong><?= Yii::t('site','Delivery Man') ?></strong></h1>
                    <br>
                    <p><strong><?= Yii::t('site','Daily Sign In') ?></strong></p>
                    <p align = "justify">In order to be assigned deliveries, you are required to click on the "Sign In" button provided in the Daily Sign In for Delivery Man to prove that you are active and willing to deliver for today. 
                    You will not be receiving any deliveries without pressing the "Sign In" button.<?= Yii::t('site','dman-1') ?></p>
                    <br>
                    <br>
                    <p><strong><?= Yii::t('site','Incoming Deliveries') ?></strong></p>
                    <p align = "justify">You will receive a notification every time you are assigned an order. Orders assigned to you will be in the same area as your provided address. Every order you receive must be accepted unless you provide a valid reason to us, should a number of orders 
                    be neglected, action will be taken against you. Delivery status <strong>must</strong> be updated accordingly.<?= Yii::t('site','dman-2') ?></p>
                    <br>
                    <br>


                </div>
            </div>
        </div>

        <div id="faq" class="tab-pane fade">
            <h1><strong>Frequently Asked Questions<?= Yii::t('faq','Frequently Asked Questions') ?></strong></h1>
            <br>
            <ul>
                <li>
                    <strong><?= Yii::t('faq','Q') ?>:</strong> 
                    What if I encounter a problem with my order?<?= Yii::t('faq','faq-1-q') ?>
                </li>
                <li>
                    <strong><?= Yii::t('faq','A') ?>:</strong> 
                    Kindly contact our customer service and provide your Delivery ID before describing your problem.<?= Yii::t('faq','faq-1-a') ?>
                </li>
                <br>

                <li>
                    <strong><?= Yii::t('faq','Q') ?>:</strong> 
                    My postcode is not listed. What should I do?<?= Yii::t('faq','faq-2-q') ?>
                </li>
                <li><strong><?= Yii::t('faq','A') ?>:</strong> 
                    Our services are currently expanding. Do wait until your area is available.<?= Yii::t('faq','faq-2-a') ?>
                </li>
                <br>

                <li>
                    <strong><?= Yii::t('faq','Q') ?>:</strong> 
                    Can I cancel an order that I have already placed?<?= Yii::t('faq','faq-3-q') ?>
                </li>
                <li>
                    <strong><?= Yii::t('faq','A') ?>:</strong> 
                    No, you cannot cancel an order that is successfully placed.<?= Yii::t('faq','faq-3-a') ?>
                </li>
                <br>

                <li>
                    <strong><?= Yii::t('faq','Q') ?>:</strong> 
                    Where can I see my ongoing orders or my completed orders?<?= Yii::t('faq','faq-4-q') ?>
                </li>
                <li>
                    <strong><?= Yii::t('faq','A') ?>:</strong> 
                    You can see your ongoing orders under <strong>My Profile > My Orders</strong> and your completed orders under <strong>My profile > Order History</strong>.<?= Yii::t('faq','faq-4-a') ?>
                </li>
                <br>

                <li>
                    <strong><?= Yii::t('faq','Q') ?>:</strong> 
                    Can I withdraw my account balance?<?= Yii::t('faq','faq-5-q') ?>
                </li>
                <li>
                    <strong><?= Yii::t('faq','A') ?>:</strong> 
                    Yes, you may withdraw the balance left in your account, but we will be deducting RM 2.00 as procedure fee.<?= Yii::t('faq','faq-5-a') ?>
                </li>
                <br>

                <li>
                    <strong><?= Yii::t('faq','Q') ?>:</strong> 
                    Can I upgrade my account to a Restaurant Manager or a Delivery Man from a member account?<?= Yii::t('faq','faq-6-q') ?>
                </li>
                <li>
                    <strong><?= Yii::t('faq','A') ?>:</strong> 
                    Yes, you may upgrade your account to either a Restaurant Manager or a Delivery Man but you may not be both of that. A Restaurant Manager or Delivery Man account has the features of a member account.<?= Yii::t('faq','faq-6-a') ?>
                </li>
                <br>

                <li>
                    <strong><?= Yii::t('faq','Q') ?>:</strong> 
                    Can i downgrade my account from a Delivery Man / Restaurant Manager to a member account?<?= Yii::t('faq','faq-7-q') ?>
                </li>
                <li>
                    <strong><?= Yii::t('faq','A') ?>:</strong> 
                    No, you cannot downgrade your account.<?= Yii::t('faq','faq-7-a') ?>
                </li>
                <br>

                <li>
                    <strong><?= Yii::t('faq','Q') ?>:</strong> 
                    Why can't I place my order before 9:30am and after 11:00am daily?<?= Yii::t('faq','faq-8-q') ?>
                </li>
                <li><strong><?= Yii::t('faq','A') ?>:</strong> 
                    We are currently still in our beta phase, thus we only offer lunch deliveries for now.<?= Yii::t('faq','faq-8-a') ?>
                </li>
                <br>

                <li>
                    <strong><?= Yii::t('faq','Q') ?>:</strong> 
                    What if I want to avoid certain ingredients in my order due to allergies etc.?<?= Yii::t('faq','faq-9-q') ?>
                </li>
                <li>
                    <strong><?= Yii::t('faq','A') ?>:</strong> 
                    Kindly write down what you would like to avoid in the remarks section provided.<?= Yii::t('faq','faq-9-a') ?>
                </li>
                <br>

                <li>
                    <strong><?= Yii::t('faq','Q') ?>:</strong> 
                    Is there a minimum fee I have to hit in order to place an order?<?= Yii::t('faq','faq-10-q'); ?>
                </li>
                <li>
                    <strong><?= Yii::t('faq','A') ?>:</strong> 
                    Yes, your order must be at least RM 10.00 excluding delivery charge in order to place your order.<?= Yii::t('faq','faq-10-a') ?>
                </li>
                <br>

                <li>
                    <strong><?= Yii::t('faq','Q') ?>:</strong> 
                    How do I use the voucher or discount codes I received?<?= Yii::t('faq','faq-11-q') ?>
                </li>
                <li>
                    <strong><?= Yii::t('faq','A') ?>:</strong> 
                    You can use the codes before you check out in the voucher / discount code field provided.<?= Yii::t('faq','faq-11-a') ?>
                </li>
                <br>

                <li>
                    <strong><?= Yii::t('faq','Q') ?>:</strong> 
                    How do I invite a friend to join HamsterEat?<?= Yii::t('faq','faq-12-q') ?>
                </li>
                <li>
                    <strong><?= Yii::t('faq','A') ?>:</strong> 
                    You can provide your friend your referral link which he can use to sign up an account and get a RM 5.00 voucher which the referrer will get as well.<?= Yii::t('faq','faq-12-a') ?>
                </li>
                <br>

                <li>
                    <strong><?= Yii::t('faq','Q') ?>:</strong> 
                    How does HamsterEat differentiate areas?<?= Yii::t('faq','faq-13-q') ?>
                </li>
                <li>
                    <strong><?= Yii::t('faq','A') ?>:</strong> 
                    We mainly differentiate areas based on postcodes. However, under certain circumstances where the postcode areas are too big, we will divide it into smaller parts.<?= Yii::t('faq','faq-13-a') ?>
                </li>
                <br>

                <li>
                    <strong><?= Yii::t('faq','Q') ?>:</strong> 
                    Do I have to tip my delivery man?<?= Yii::t('faq','faq-14-q') ?>
                </li>
                <li>
                    <strong><?= Yii::t('faq','A') ?>:</strong> 
                    Tipping is not necessary but it is up to the customer's own free will.<?= Yii::t('faq','faq-14-a') ?>
                </li>
            </ul>
        </div>
    </div>
</div>