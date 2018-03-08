<?php
/**
 * Configuration file for the "yii asset" console command.
 */

// In the console environment, some path aliases may not exist. Please define these:
Yii::setAlias('@webroot', __DIR__ . '/frontend/web');
Yii::setAlias('@web', '/');

return [
    // Adjust command/callback for JavaScript files compressing:
    'jsCompressor' => 'java -jar compiler.jar --js {from} --js_output_file {to}',
    // Adjust command/callback for CSS files compressing:
    'cssCompressor' => 'java -jar yuicompressor.jar --type css {from} -o {to}',
    // Whether to delete asset source after compression:
    'deleteSource' => false,
    // The list of asset bundles to compress:
    'bundles' => [
        'frontend\assets\AddFoodAsset',
        'frontend\assets\AddStaffAsset',
        'frontend\assets\AppAsset',
        'frontend\assets\CartAsset',
        'frontend\assets\CheckoutAsset',
        'frontend\assets\CommentsAsset',
        'frontend\assets\CookingAsset',
        'frontend\assets\DeliveryLocationAsset',
        'frontend\assets\DeliverymanOrdersAsset',
        'frontend\assets\DeliverymanOrdersHistoryAsset',
        'frontend\assets\EditRestaurantDetailsAsset',
        'frontend\assets\FeedbackAsset',
        'frontend\assets\FoodDetailsAsset',
        'frontend\assets\FoodMenuAsset',
        'frontend\assets\FoodOnOffAsset',
        'frontend\assets\FoodServiceAsset',
        'frontend\assets\ManageStaffAsset',
        'frontend\assets\NewRestaurantAsset',
        'frontend\assets\MyOrdersAsset',
        'frontend\assets\MyOrdersHistoryAsset',
        'frontend\assets\MyVouchersAsset',
        'frontend\assets\NewsAsset',
        'frontend\assets\NotificationAsset',
        'frontend\assets\OrderDetailsAsset',
        'frontend\assets\PaymentAsset',
        'frontend\assets\PhotoSliderAsset',
        'frontend\assets\RatingIndexAsset',
        'frontend\assets\RestaurantDefaultIndexAsset',
        'frontend\assets\RestaurantDefaultIndex2Asset',
        'frontend\assets\RestaurantDetailsAsset',
        'frontend\assets\RestaurantEarningsAsset',
        'frontend\assets\RestaurantOrdersAsset',
        'frontend\assets\RestaurantDetailsAsset',
        'frontend\assets\RestaurantServiceAsset',
        'frontend\assets\RestaurantOrdersHistoryAsset',
        'frontend\assets\RestaurantStatisticsAsset',
        'frontend\assets\StarsAsset',
        'frontend\assets\TopupIndexAsset',
        'frontend\assets\TopupWithdrawMpHistoryAsset',
        'frontend\assets\UserAsset',
        'frontend\assets\ViewRestaurantAsset',
   
        // 'app\assets\AppAsset',
         'yii\web\YiiAsset',
         'yii\web\JqueryAsset',
    ],
    // Asset bundle for compression output:
    'targets' => [
        'addfood' =>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/addfood-{hash}.js',
            'css' => 'css/addfood-{hash}.css',
            'depends' => [
              'frontend\assets\AddFoodAsset',
            ]
        ],
        'addstaff'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/addstaff-{hash}.js',
            'css' => 'css/addstaff-{hash}.css',
            'depends' => [
              'frontend\assets\AddStaffAsset',
            ]
        ],
        'site'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/site-{hash}.js',
            'css' => 'css/site-{hash}.css',
            'depends' => [
              'frontend\assets\AppAsset',
            ]
        ],
        'cart'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/cart-{hash}.js',
            'css' => 'css/cart-{hash}.css',
            'depends' => [
              'frontend\assets\CartAsset',
              'frontend\assets\CheckoutAsset',
            ]
        ],
       
        'comment'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/comment-{hash}.js',
            'css' => 'css/comment-{hash}.css',
            'depends' => [
              'frontend\assets\CommentsAsset',
            ]
        ],
        'cooking'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/cooking-{hash}.js',
            'css' => 'css/cooking-{hash}.css',
            'depends' => [
              'frontend\assets\CookingAsset',
            ]
        ],
        'deliverylocation'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/deliverylocation-{hash}.js',
            'css' => 'css/deliverylocation-{hash}.css',
            'depends' => [
              'frontend\assets\DeliveryLocationAsset',
            ]
        ],
        'deliveryorder'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/deliveryorder-{hash}.js',
            'css' => 'css/deliveryorder-{hash}.css',
            'depends' => [
              'frontend\assets\DeliverymanOrdersAsset',
            ]
        ],
        'deliveryorderhistory'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/deliveryorderhistory-{hash}.js',
            'css' => 'css/deliveryorderhistory-{hash}.css',
            'depends' => [
              'frontend\assets\DeliverymanOrdersHistoryAsset',
            ]
        ],
        'editrestaurant'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/editrestaurant-{hash}.js',
            'css' => 'css/editrestaurant-{hash}.css',
            'depends' => [
              'frontend\assets\EditRestaurantDetailsAsset',
            ]
        ],
        'feedback'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/feedback-{hash}.js',
            'css' => 'css/feedback-{hash}.css',
            'depends' => [
              'frontend\assets\FeedbackAsset',
            ]
        ],
        'food'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/food-{hash}.js',
            'css' => 'css/food-{hash}.css',
            'depends' => [
              'frontend\assets\FoodDetailsAsset',
            ]
        ],
        'menu'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/menu-{hash}.js',
            'css' => 'css/menu-{hash}.css',
            'depends' => [
              'frontend\assets\FoodMenuAsset',
            ]
        ],
        'fonoff'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/fonoff-{hash}.js',
            'css' => 'css/fonoff-{hash}.css',
            'depends' => [
              'frontend\assets\FoodOnOffAsset',
            ]
        ],
        'fservice'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/fservice-{hash}.js',
            'css' => 'css/fservice-{hash}.css',
            'depends' => [
              'frontend\assets\FoodServiceAsset',
            ]
        ],
        'staff'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/staff-{hash}.js',
            'css' => 'css/staff-{hash}.css',
            'depends' => [
              'frontend\assets\ManageStaffAsset',
            ]
        ],
        'myorder'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/myorder-{hash}.js',
            'css' => 'css/myorder-{hash}.css',
            'depends' => [
              'frontend\assets\MyOrdersAsset',
            ]
        ],
        'myorderhs'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/myorderhs-{hash}.js',
            'css' => 'css/myorderhs-{hash}.css',
            'depends' => [
              'frontend\assets\MyOrdersHistoryAsset',
            ]
        ],
        'voucher'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/voucher-{hash}.js',
            'css' => 'css/voucher-{hash}.css',
            'depends' => [
              'frontend\assets\MyVouchersAsset',
            ]
        ],
        'newres'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/newres-{hash}.js',
            'css' => 'css/newres-{hash}.css',
            'depends' => [
              'frontend\assets\NewRestaurantAsset',
            ]
        ],
        'news'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/news-{hash}.js',
            'css' => 'css/news-{hash}.css',
            'depends' => [
              'frontend\assets\NewsAsset',
            ]
        ],
        'notic'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/notic-{hash}.js',
            'css' => 'css/notic-{hash}.css',
            'depends' => [
              'frontend\assets\NotificationAsset',
            ]
        ],
        'orderdetail'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/orderdetail-{hash}.js',
            'css' => 'css/orderdetail-{hash}.css',
            'depends' => [
              'frontend\assets\OrderDetailsAsset',
            ]
        ],
        'payment'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/payment-{hash}.js',
            'css' => 'css/payment-{hash}.css',
            'depends' => [
              'frontend\assets\PaymentAsset',
            ]
        ],
        'slider'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/slider-{hash}.js',
            'css' => 'css/slider-{hash}.css',
            'depends' => [
              'frontend\assets\PhotoSliderAsset',
            ]
        ],
        'rating'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/rating-{hash}.js',
            'css' => 'css/rating-{hash}.css',
            'depends' => [
              'frontend\assets\RatingIndexAsset',
            ]
        ],
        'resdetail2'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/resdetail2-{hash}.js',
            'css' => 'css/resdetail2-{hash}.css',
            'depends' => [
              'frontend\assets\RestaurantDefaultIndex2Asset',
            ]
        ],
        'resdetail1'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/resdetail1-{hash}.js',
            'css' => 'css/resdetail1-{hash}.css',
            'depends' => [
              'frontend\assets\RestaurantDefaultIndexAsset',
            ]
        ],
        'resdetail'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/resdetail-{hash}.js',
            'css' => 'css/resdetail-{hash}.css',
            'depends' => [
              'frontend\assets\RestaurantDetailsAsset',
            ]
        ],
        'earning'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/earning-{hash}.js',
            'css' => 'css/earning-{hash}.css',
            'depends' => [
              'frontend\assets\RestaurantEarningsAsset',
            ]
        ],
        'resorder'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/resorder-{hash}.js',
            'css' => 'css/resorder-{hash}.css',
            'depends' => [
              'frontend\assets\RestaurantOrdersAsset',
            ]
        ],
        'resorderhis'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/resorderhis-{hash}.js',
            'css' => 'css/resorderhis-{hash}.css',
            'depends' => [
              'frontend\assets\RestaurantOrdersHistoryAsset',
            ]
        ],
        'resorderserv'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/resorderserv-{hash}.js',
            'css' => 'css/resorderserv-{hash}.css',
            'depends' => [
              'frontend\assets\RestaurantServiceAsset',
            ]
        ],
        'statis'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/statis-{hash}.js',
            'css' => 'css/statis-{hash}.css',
            'depends' => [
              'frontend\assets\RestaurantStatisticsAsset',
            ]
        ],
        'star'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/star-{hash}.js',
            'css' => 'css/star-{hash}.css',
            'depends' => [
              'frontend\assets\StarsAsset',
            ]
        ],
        'topup'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/topup-{hash}.js',
            'css' => 'css/topup-{hash}.css',
            'depends' => [
              'frontend\assets\TopupIndexAsset',
            ]
        ],
        'accounthistory'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/accounthistory-{hash}.js',
            'css' => 'css/accounthistory-{hash}.css',
            'depends' => [
              'frontend\assets\TopupWithdrawMpHistoryAsset',
            ]
        ],
        'user'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/user-{hash}.js',
            'css' => 'css/user-{hash}.css',
            'depends' => [
              'frontend\assets\UserAsset',
            ]
        ],
        'viewres'=>[
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/viewres-{hash}.js',
            'css' => 'css/viewres-{hash}.css',
            'depends' => [
              'frontend\assets\ViewRestaurantAsset',
            ]
        ],
        'all' => [
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/all-{hash}.js',
            'css' => 'css/all-{hash}.css',
        ],
    ],
    // Asset manager configuration:
    'assetManager' => [
        'basePath' => '@webroot/assets',
        'baseUrl' => '@web/assets',
    ],
];