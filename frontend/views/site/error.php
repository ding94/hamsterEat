<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<style>

.page-wrap{
    background-color: #fff;
}

.site-error{
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
}

.site-error a{
    font-size: 24px;
    margin: 20px;
}

.error-404-container{
    display: flex;
    align-items: center;
}

.error-404-container img{
    height: 400px;
}

.error-404-container span{
    text-shadow: 1px 1px #FFDA00, 
                2px 2px #FFDA00, 
                3px 3px #FFDA00,
                4px 4px #FFDA00,
                5px 5px #FFDA00,
                6px 6px #FFDA00,
                7px 7px #FFDA00,
                8px 8px #FFDA00,
                9px 9px #FFDA00,
                10px 10px #FFDA00,
                11px 11px #FFDA00,
                12px 12px #FFDA00;
}

.error-text-1{
    font-size: 90px;
    font-weight: bold;
}

.error-text-2{
    font-size: 48px;
}

.error-404-container span{
    font-size: 400px;
}

@media (max-width: 991px){
    .error-404-container span{
        font-size: 200px;
    }

    .error-404-container img{
        height: 200px;
    }

    .error-text-1{
        font-size: 45px;
    }

    .error-text-2{
        font-size: 24px;
    }

    .site-error a{
        font-size: 12px;
    }
}

@media (max-width: 500px){
    .error-404-container span{
        font-size: 120px;
    }

    .error-404-container img{
        height: 120px;
    }

    .error-text-1{
        font-size: 45px;
    }

    .error-text-2{
        font-size: 24px;
    }

    .site-error a{
        font-size: 12px;
    }
}

</style>

<div class="site-error container">
    
    <div class="error-404-container">
        <span>4</span>
        <?php echo Html::img('@web/SysImg/Icon.png'); ?>
        <span>4</span>
    </div>
    <span class="error-text-1">Oooopss!</span>
    <span class="error-text-2">We can't seem to find the page you are looking for.</span>
    <div><?php echo Html::a('Go Home',['site/index'],['class'=>'btn main-btn raised-btn']); ?></div>

    <!-- <p>
        Yii::t('site','The above error occurred while the Web server was processing your request.') 
    </p> -->
   <!--  <p>
        Yii::t('site','Please contact us if you think this is a server error.') Yii::t('site','Thank you.') 
    </p> -->

</div>
