<?php
use yii\helpers\Html;

$_csrfT = \Yii::$app->request->csrfToken;
$_params = \yii\helpers\Json::htmlEncode($params);
$this->registerJs("
    var _params_$binar_id  =    {$_params};
    var _binar_id          = '{$binar_id}';
    var _csrfT            = '{$_csrfT}';
",\yii\web\View::POS_HEAD);

?>

<div class="container-fluid">
    <div class="row">
        <div id="<?=$binar_id;?>" class="binar">
        </div>
    </div>
    <?php if ($params['mode'] === 'update'):?>
        <div id="actionButtons_<?=$binar_id;?>" class="row" align="center" style="padding: 15px">
            <?php
            echo Html::button('<span class="glyphicon glyphicon-plus"></span>', [
                'title' => \Yii::t('app', 'Добавить потомка'),
                'id' => 'btn_' . $binar_id . '_appendChild',
                'class' => 'actionBtn',
            ]);
            echo Html::button('<span class="glyphicon glyphicon-trash"></span>', [
                'title' => \Yii::t('app', 'Удалить вместе с потомками'),
                'id' => 'btn_' . $binar_id . '_deleteItem',
                'class' => 'actionBtn ',
            ]);
            ?>
        </div>
    <?php endif;?>
</div>
