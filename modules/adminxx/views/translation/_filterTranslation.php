<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\adminxx\assets\AdminxxUserFilterAsset;

AdminxxUserFilterAsset::register($this);


$_exportQuery = \yii\helpers\Json::htmlEncode($exportQuery);
$this->registerJs("
    var _exportQuery      = {$_exportQuery};
",\yii\web\View::POS_HEAD);

?>


<div class="container-fluid">
    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'post',
        'id' => 'userFilterForm',
    ]);
    ?>
    <div class="xCard">

        <div class="row">
            <div class="col-md-12 col-lg-6 ">
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <?php
                        echo $form->field($filter, 'checkedIdsJSON')->textarea([
                                'cols' => 30, 'rows' => '3',
                        ])->hiddenInput()->label(false);
                        ?>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <?php
                        ?>
                        <div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 ">
                <?php
                echo $form->field($filter, 'showOnlyChecked')->checkbox([
                      //  'onchange' => 'checkOnlyChecked(this);'
                ]);
                ?>
            </div>
        </div>
        <div class="row">
            <div class="form-group" align="center" style="padding: 20px">
                <?php
              //  echo  Html::submitButton('Шукати', ['class' => 'btn btn-primary', 'id' => 'subBtn']);
                echo  Html::button('Шукати', [
                    'class' => 'btn btn-primary',
                    'id' => 'subBtn',
                    'onclick' => 'useFilter();'
                    ]);
                ?>
                <?= Html::button('Очистити фільтр', [
                    'class' => 'btn btn-danger',
                    'id' => 'cleanBtn',
                    'onclick' => 'cleanFilter(true);',
                ]) ?>
                <?= Html::button('В файл', [
                    'class' => 'btn btn-success',
                    'onclick' => 'startBackgroundUploadTask();',
                ]) ?>
            </div>
        </div>

    </div>
    <?php ActiveForm::end(); ?>
</div>

<script>
$(document).ready(function(){

});
</script>


