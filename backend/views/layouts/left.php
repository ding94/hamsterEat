<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?php echo Yii::$app->user->identity->adminname?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

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
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [   'label' => 'Admin Controller' , 'url' => '#', 'icon' => 'lock',
                        'items' =>  [
                                        [ 'label' => 'Admin List', 'icon' => 'circle-o', 'url' => ['/admin/index']],
                                    ],
                        'options' => ['class' => 'active'],
                    ],
                    [   'label' => 'User Controller', 'icon' => 'user', 'url' => "#",
                        'items' =>  [
                                        [ 'label' => 'User List', 'icon' => 'circle-o', 'url' => ['/user/index']],
                                    ],
                        'options' => ['class' => 'active'],
                    ],
					[   'label' => 'Finance Controller', 'icon' => 'money', 'url' => '#',
                        'items' =>  [
                                        ['label' => 'Offline Topup', 'icon' => 'circle-o', 'url' => ['/finance/topup/index']],
                                    ],
                        'options' => ['class' => 'active'],

                    ],
                    [   'label' => 'Ticket Controller' , 'icon' => 'cog' ,'url' => '#',
                        'items' =>  [
                                        ['label' => 'Ticket List' ,'icon' => 'circle-o' , 'url' => ['/ticket/index']],
                                        ['label' => 'Completed Ticket List' ,'icon' => 'circle-o' , 'url' => ['/ticket/complete']],
                                    ],
                        'options' => ['class' => 'active'],
                    ],
                    [   'label' => 'Voucher Controller' , 'icon' => 'cog' ,'url' => '#',
                        'items' =>  [
                                        ['label' => 'Voucher List' ,'icon' => 'circle-o' , 'url' => ['/vouchers/index']],
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
