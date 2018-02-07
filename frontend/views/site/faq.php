<?php $this->title = Yii::t('common','Guide'); ?>

<div class = "container guide">
    <ul class="nav nav-pills ul-center">
        <li class="active"><a data-toggle="pill" href="#guide"><h4><?= Yii::t('common','Guide') ?></h4></a></li>
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
                    <p align = "justify"><?= Yii::t('site','general-1') ?></p>
                    <table class= table table-user-info>
                        <tr>
                            <th><?= Yii::t('site','Member') ?></th>
                            <td><?= Yii::t('site','general-1-1') ?></td>
                        </tr>
                        <tr>
                            <th><?= Yii::t('site','Restaurant Manager') ?></th>
                            <td><?= Yii::t('site','general-1-2') ?></td>
                        </tr>
                        <tr>
                            <th> <?= Yii::t('site','Delivery Man') ?></th>
                            <td><?= Yii::t('site','general-1-3') ?></td>
                        </tr>
                    </table>
                    <br>
                    <br>
                    <br>
                    <p><strong><?= Yii::t('site','Placing an Order') ?></strong></p>
                    <p align = "justify"><?= Yii::t('site','general-2') ?></p>
                    <br>
                    <br>
                    <p><strong><?= Yii::t('common','Payment') ?></strong></p>
                    <p align = "justify"><?= Yii::t('faq','general-3') ?></p>
                    <table class= table table-user-info>
                        <tr>
                            <th> <?= Yii::t('site','Account Balance') ?></th>
                            <td><?= Yii::t('site','general-3-1') ?></td>
                        </tr>
                        <tr>
                            <th><?= Yii::t('site','Cash on Delivery') ?></th>
                            <td><?= Yii::t('site','general-3-2') ?></td>
                        </tr>
                    </table>
                    <br>
                    <br>
                    <br>
                    <p><strong><?= Yii::t('site','Account Upgrade') ?></strong></p>
                    <p align = "justify"><?= Yii::t('site','general-4') ?></p>
                </div>

                <div id="restaurantmanager" class="tab-pane fade">
                    <h1><strong><?= Yii::t('site','Restaurant Manager') ?></strong></h1>
                    <br>
                    <p><strong><?= Yii::t('site','Incoming Orders') ?></strong></p>
                    <p align = "justify"><?= Yii::t('site','rmanager-1') ?></p>
                    <br>
                    <br>
                    <p><strong><?= Yii::t('site','Setting Up a Restaurant') ?></strong></p>
                    <p align = "justify"><?= Yii::t('site','rmanager-2') ?></p>
                    <br>
                    <br>
                    <p><strong><?= Yii::t('site','Sold Out') ?> / <?= Yii::t('site','Unavailable Item') ?></strong></p>
                    <p align = "justify"><?= Yii::t('site','rmanager-3') ?></p>
                    <br>
                    <br>
                    <p><strong><?= Yii::t('site','Close Restaurant') ?></strong></p>
                    <p align = "justify"><?= Yii::t('site','rmanager-4') ?></p>
                </div>

                <div id="deliveryman" class="tab-pane fade">
                    <h1><strong><?= Yii::t('site','Delivery Man') ?></strong></h1>
                    <br>
                    <p><strong><?= Yii::t('site','Daily Sign In') ?></strong></p>
                    <p align = "justify"><?= Yii::t('site','dman-1') ?></p>
                    <br>
                    <br>
                    <p><strong><?= Yii::t('site','Incoming Deliveries') ?></strong></p>
                    <p align = "justify"><?= Yii::t('site','dman-2') ?></p>
                    <br>
                    <br>


                </div>
            </div>
        </div>

        <div id="faq" class="tab-pane fade">
            <h1><strong><?= Yii::t('faq','Frequently Asked Questions') ?></strong></h1>
            <br>
            <ul>
                <li><strong><?= Yii::t('faq','Q') ?>:</strong> <?= Yii::t('faq','faq-1-q') ?></li>
                <li><strong><?= Yii::t('faq','A') ?>:</strong><?= Yii::t('faq','faq-1-a') ?></li>
                <br>

                <li><strong><?= Yii::t('faq','Q') ?>:</strong><?= Yii::t('faq','faq-2-q') ?></li>
                <li><strong><?= Yii::t('faq','A') ?>:</strong><?= Yii::t('faq','faq-2-a') ?></li>
                <br>

                <li><strong><?= Yii::t('faq','Q') ?>:</strong><?= Yii::t('faq','faq-3-q') ?></li>
                <li><strong><?= Yii::t('faq','A') ?>:</strong><?= Yii::t('faq','faq-3-a') ?></li>
                <br>

                <li><strong><?= Yii::t('faq','Q') ?>:</strong><?= Yii::t('faq','faq-4-q') ?></li>
                <li><strong><?= Yii::t('faq','A') ?>:</strong><?= Yii::t('faq','faq-4-a') ?></li>
                <br>

                <li><strong><?= Yii::t('faq','Q') ?>:</strong><?= Yii::t('faq','faq-5-q') ?></li>
                <li><strong><?= Yii::t('faq','A') ?>:</strong><?= Yii::t('faq','faq-5-a') ?></li>
                <br>

                <li><strong><?= Yii::t('faq','Q') ?>:</strong><?= Yii::t('faq','faq-6-q') ?></li>
                <li><strong><?= Yii::t('faq','A') ?>:</strong><?= Yii::t('faq','faq-6-a') ?></li>
                <br>

                <li><strong><?= Yii::t('faq','Q') ?>:</strong><?= Yii::t('faq','faq-7-q') ?></li>
                <li><strong><?= Yii::t('faq','A') ?>:</strong><?= Yii::t('faq','faq-7-a') ?></li>
                <br>

                <li><strong><?= Yii::t('faq','Q') ?>:</strong><?= Yii::t('faq','faq-8-q') ?></li>
                <li><strong><?= Yii::t('faq','A') ?>:</strong><?= Yii::t('faq','faq-8-a') ?></li>
                <br>

                <li><strong><?= Yii::t('faq','Q') ?>:</strong><?= Yii::t('faq','faq-9-q') ?></li>
                <li><strong><?= Yii::t('faq','A') ?>:</strong><?= Yii::t('faq','faq-9-a') ?></li>
                <br>

                <li><strong><?= Yii::t('faq','Q') ?>:</strong><?= Yii::t('faq','faq-10-q'); ?></li>
                <li><strong><?= Yii::t('faq','A') ?>:</strong><?= Yii::t('faq','faq-10-a') ?></li>
                <br>

                <li><strong><?= Yii::t('faq','Q') ?>:</strong><?= Yii::t('faq','faq-11-q') ?></li>
                <li><strong><?= Yii::t('faq','A') ?>:</strong><?= Yii::t('faq','faq-11-a') ?></li>
                <br>

                <li><strong><?= Yii::t('faq','Q') ?>:</strong> <?= Yii::t('faq','faq-12-q') ?></li>
                <li>
                    <strong><?= Yii::t('faq','A') ?>:</strong><?= Yii::t('faq','faq-12-a') ?>
                </li>
                <br>

                <li><strong><?= Yii::t('faq','Q') ?>:</strong><?= Yii::t('faq','faq-13-q') ?></li>
                <li><strong><?= Yii::t('faq','A') ?>:</strong><?= Yii::t('faq','faq-13-a') ?></li>
                <br>

                <li><strong><?= Yii::t('faq','Q') ?>:</strong><?= Yii::t('faq','faq-14-q') ?></li>
                <li><strong><?= Yii::t('faq','A') ?>:</strong><?= Yii::t('faq','faq-14-a') ?></li>
            </ul>
        </div>
    </div>
</div>