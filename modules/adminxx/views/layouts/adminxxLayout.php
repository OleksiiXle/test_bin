<?php

use yii\helpers\Html;
use app\widgets\menuX\MenuXWidget;
use app\modules\adminxx\assets\AdminxxLayoutAsset;
use app\assets\BackgroundTaskAsset;
use yii\jui\JuiAsset;

AdminxxLayoutAsset::register($this);
BackgroundTaskAsset::register($this);
JuiAsset::register($this);

if (Yii::$app->session->getAllFlashes()){
         $fms = Yii::$app->session->getAllFlashes();
         $_fms = \yii\helpers\Json::htmlEncode($fms);
         $this->registerJs("var _fms = {$_fms};",\yii\web\View::POS_HEAD);
}

?>
<?php
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => \yii\helpers\Url::to(['/images/sun_61831.png'])]);?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ;?>

<div id="mainContainer" class="container-fluid">
    <!--************************************************************************************************************* HEADER-->
    <div class="xLayoutHeader">

        <!--************************************************************************************************************* MENU BTN-->
        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" align="left" style="padding-left: 2px; padding-right: 0">
            <!--  <button id="open-menu-btn" onclick="showModal(500,600, 'lokoko the best');" class="xMenuBtn" >-->
            <a href="/adminxx" title="На гоговну сторінку">
                 <span class ="img-rounded">
                        <img  src="<?=\yii\helpers\Url::to('@web/images/sun_61831.png');?>" height="40px" width="40px;">
                 </span>
            </a>
            <button id="open-menu-btn" onclick="showMenu();" class="xMenuBtn" >
                  <span class="glyphicon glyphicon-list" ></span>
              </button>
          </div>
          <!--************************************************************************************************************* CENTER-->

        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 " >
            <h3 style="margin-top: 15px;margin-bottom: 15px; white-space: nowrap; overflow: hidden;"><?= Html::encode($this->title) ?></h3>
        </div>
        <!--************************************************************************************************************* LOGIN/LOGOUT-->
        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" align="center" style="padding-left: 1px">
            <?php
            if (!Yii::$app->user->isGuest){
                $icon = \yii\helpers\Url::to('@web/images/log_logout_door_1563.png');
                echo Html::beginForm(['/site/logout'], 'post');
                echo Html::submitButton(
                    '<span> <img  src="' . $icon . '" height="30px" width="30px;">' . Yii::$app->user->getIdentity()->username .  '</span>',
                    ['class' => 'btn btn-link ']
                );
                echo Html::endForm();
            }
            ?>
        </div>
    </div>
    <div class="xLayoutContent">

        <div id="flashMessage" style="display: none">
        </div>

        <?= $content ?>
        <div class="xFooter">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <p>О сколько нам открытий чудных готовит просвещенья дух</p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div>
                    И опыт, сын ошибок трудных
                </div>
                <div>
                    И гений, просвещенья друг ...
                    <br>
                    А.С. Пушкин
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <br>
                 Lokoko inc. LTD - <?= date('Y') ?>
            </div>
        </div>
    </div>

</div>


<div id="xWrapper">
    <div id="xCover" ></div>
    <div id="xMenu" onclick="menuClick()">
        <div id="xMenuContent" >
        <button class="xMenuCloseBtn" onclick="hideMenu();">
            <span class ="img-rounded">
                <img  src="<?=\yii\helpers\Url::to('@web/images/sun_61831.png');?>" height="50px" width="50px;">
            </span>

        </button>
        <div class="menuTree">
            <?php
            echo MenuXWidget::widget([
                'showLevel' => '1',
                'accessLevels' => [0,2]
            ]) ;
            ?>
        </div>

    </div>
    </div>
    <div id="xModal">
        <div id="xModalWindow">
            <table class="table xModalHeader">
                <tr>
                    <td>
                      <span id="xModalHeader"></span>

                    </td>
                    <td align="right">
                        <button id="xModalCloseBtn" onclick="hideModal();">
                            <span class="glyphicon glyphicon-remove-circle" ></span>
                        </button>
                    </td>
                </tr>
            </table>
            <div id="xModalContent">
                <b>lokoko</b>
            </div>
        </div>
    </div>


</div>


<div id="preloaderCommonLayout" style="display: none">
    <div class="page-loader-circle"></div>
    <div id="preloaderText"></div>
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<script>
</script>


