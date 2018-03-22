<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
                </div>
                <div class="pull-left info">
                    <p><?php echo Yii::$app->user->identity->adminname?></p>

                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
        -->

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Control Page', 'options' => ['class' => 'header']],
                    //['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    ['label' => 'Available Controllers', 'icon' => 'fas fa-cog', 'url' => ['/site/controllers'],],
                    ['label' => 'Change Password', 'icon' => 'fas fa-cog', 'url' => ['/site/change-password'],],
                    ['label' => 'Logout', 'icon' => 'fas fa-sign-out', 'url' => ['/site/logout'],'template' => '<a href="{url}" data-method="post">{label}</a>',],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [   'label' => 'Admin Controller' , 'url' => '#', 'icon' => 'lock',
                        'items' =>  [
                                        [ 'label' => 'Admin List', 'icon' => 'circle-o', 'url' => ['/admin/index']],
                                        [ 'label' => 'R.Manager Approve', 'icon' => 'circle-o', 'url' => ['/restaurant/default/rmanager_approval']],
                                        [ 'label' => 'Restaurant Approve', 'icon' => 'circle-o', 'url' => ['/restaurant/default/restaurant_approval']],
                                    ],
                        'options' => ['class' => 'active'],
                        'visible'=> Yii::$app->user->can('admin/index'),
                    ],
					[   'label' => 'Bank Controller', 'icon' => 'bank', 'url' => "#",
                        'items' =>  [
                                        [ 'label' => 'Bank List', 'icon' => 'circle-o', 'url' => ['/bank/index']],
                                    ],
                        'options' => ['class' => 'active'],
                    ],
                    [   'label' => 'Banner And News ' , 'icon' => 'square' ,'url' => '#',
                        'items' =>  [
                                        ['label' => 'Banner List' ,'icon' => 'circle-o' , 'url' => ['/banner/index']],
                                        ['label' => 'Add Banner' ,'icon' => 'circle-o' , 'url' => ['/banner/addbanner']],
                                        ['label' => 'News List' ,'icon' => 'circle-o' , 'url' => ['/news/index']],
                                        ['label' => 'Add News' ,'icon' => 'circle-o' , 'url' => ['/news/addnews']],
                                    ],
                        'options' => ['class' => 'active'],
                    ],
                    [   'label' => 'User Controller', 'icon' => 'user', 'url' => "#",
                        'items' =>  [
                                        [ 'label' => 'User List', 'icon' => 'circle-o', 'url' => ['/user/index']],
                                    ],
                        'options' => ['class' => 'active'],
                    ],
                    [   'label' => 'Order Controller', 'icon' => 'user', 'url' => "#",
                        'items' =>  [
                                        ['label' => ' All Delivery  List' ,'icon' => 'circle-o' , 'url' => ['/order/all-order/index']],
                                        ['label' => ' All Order Item List' ,'icon' => 'circle-o' , 'url' => ['/order/all-order/item']],
                                        ['label' => 'Delivery Pending List' ,'icon' => 'circle-o' , 'url' => ['/order/default/index']],
                                        [ 'label' => 'Order Pending List', 'icon' => 'circle-o', 'url' => ['/order/default/order']],
                                        [ 'label' => 'Cancelled Order List', 'icon' => 'circle-o', 'url' => ['/customerservice/pausedorder']],
                                        [ 'label' => 'Solved Order List', 'icon' => 'circle-o', 'url' => ['/customerservice/comproblem']],
                                        ['label' => 'Delivery Earning','icon' => 'circle-o','url'=>['/order/profit/index']],
                                    ],
                        'options' => ['class' => 'active'],
                    ],
                    [
                        'label'=>'Notification Controller','icon'=>'bell','url'=>'#',
                        'items'=>[
                            ['label'=>'Setting','icon'=>'circle-o','url'=>['/notification/setting']],
                            ['label'=>'Sms Log','icon'=>'circle-o','url'=>['/notification/sms-log']],
                        ],
                         'options' => ['class' => 'active'],
                    ],
					[   'label' => 'Finance Controller', 'icon' => 'money', 'url' => '#',
                        'items' =>  [
                                        ['label' => 'Offline Topup', 'icon' => 'circle-o', 'url' => ['/finance/topup/index']],
                                        ['label' => 'Withdraw', 'icon' => 'circle-o', 'url' => ['/finance/withdraw/index']],
                                        ['label' => 'Force Account', 'icon' => 'circle-o', 'url' => ['/finance/accountforce/index']],
                                    ],
                        'options' => ['class' => 'active'],

                    ],

                    [   'label' => 'Ticket Controller' , 'icon' => 'cog' ,'url' => '#',
                        'items' =>  [
                                        ['label' => 'Ticket List' ,'icon' => 'circle-o' , 'url' => ['/ticket/index']],
                                        ['label' => 'Completed Ticket List' ,'icon' => 'circle-o' , 'url' => ['/ticket/complete']],
                                        ['label' => 'Feedback List' ,'icon' => 'circle-o' , 'url' => ['/feedback/index']],
                                    ],
                        'options' => ['class' => 'active'],
                    ],
                    [   'label' => 'Voucher Controller' , 'icon' => 'cog' ,'url' => '#',
                        'items' =>  [
                                        ['label' => 'Voucher List' ,'icon' => 'circle-o' , 'url' => ['/vouchers/index']],
                                        ['label' => 'User Voucher List' ,'icon' => 'circle-o' , 'url' => ['/uservoucher/index']],
                                        ['label' => "Special Voucher" ,'icon' => 'circle-o' , 'url' => ['/vouchers/specific']],
                                    ],
                        'options' => ['class' => 'active'],
                    ],
                    [
                        'label' => 'Restaurant Controller' ,'icon' => 'cutlery' ,   'url' => '#',
                        'items' => [
                                        ['label' => 'Restaurant Detail' , 'icon' => 'circle-o' , 'url' => ['/restaurant/default/index']],
                                        ['label' => 'Food Prepare Speed Detail' , 'icon' => 'circle-o' , 'url' => ['/restaurant/restaurant/show-restaurants']],
                                        ['label' => 'All Food' , 'icon' => 'circle-o' , 'url' => ['/restaurant/food/index','id' => 0]],
                                        ['label' => 'Rating' , 'icon' => 'circle-o' ,'url' => ['/rating/index']],
                                        ['label' => 'Restaurant Ranking' , 'icon' => 'circle-o' ,'url' => ['/restaurant/restaurant/restaurant-ranking-per-month']],
                                        ['label' => 'Food Ranking' , 'icon' => 'circle-o' ,'url' => ['/restaurant/food/food-ranking-per-month']],
                                   ],
                        'options' => ['class' => 'active'],

                    ],
                    [
                        'label' => 'Translate Controller' ,'icon' => 'cutlery' ,   'url' => '#',
                        'items' => [
                                        ['label' => 'Translate' , 'icon' => 'circle-o' , 'url' => ['/translate/index']],
                                        ['label' => 'FAQ' , 'icon' => 'circle-o' , 'url' => ['/translate/index','case'=>3]],
                                        ['label' => 'Guide' , 'icon' => 'circle-o' , 'url' => ['/translate/index','case'=>4]],
                                   ],
                        'options' => ['class' => 'active'],

                    ],
                    [
                        'label' => 'Company Controller' ,'icon' => 'cutlery' ,   'url' => '#',
                        'items' => [
                                        ['label' => 'Company List' , 'icon' => 'circle-o' , 'url' => ['/company/index']],
                                   ],
                        'options' => ['class' => 'active'],

                    ],
                    [
                        'label' => 'Delivery Controller' ,'icon' => 'car' ,   'url' => '#',
                        'items' => [
                                        ['label' => 'Daily Sign In ' , 'icon' => 'circle-o' , 'url' => ['/deliveryman/daily-signin' ,'month' => date("Y-m"),'day' => date("d")]],
                                   ],
                        'options' => ['class' => 'active'],

                    ],
                    [
                        'label' => 'Report Controller' ,'icon' => 'flag' ,   'url' => '#',
                        'items' => [
                                        ['label' => 'All Reports' , 'icon' => 'circle-o' , 'url' => ['/report/index']],
                                   ],
                        'options' => ['class' => 'active'],

                    ],
                    [   'label' => 'Auth Controller' , 'icon' => 'cog' ,'url' => '#',
                        'items' =>  [
                                        ['label' => 'Auth List' ,'icon' => 'circle-o' , 'url' => ['/auth/index']],
                                        ['label' => 'Permission List' ,'icon' => 'circle-o' , 'url' => ['/auth/permission']],
                                    ],
                        'options' => ['class' => 'active'],
                    ],
                            
                        
                ],
                
            ]
        ) ?>

    </section>

</aside>
