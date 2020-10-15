<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \app\modules\adminx\models\UserData;

\app\modules\adminxx\assets\AdminxxUserFilterAsset::register($this);

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
                        echo $form->field($filter, 'username');
                        echo $form->field($filter, 'last_name');
                        echo $form->field($filter, 'first_name');
                        echo $form->field($filter, 'middle_name');
                        echo $form->field($filter, 'emails');
                        ?>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <?php
                        echo $form->field($filter, 'role', ['inputOptions' =>
                            ['class' => 'form-control', 'tabindex' => '4']])
                            ->dropDownList($filter->roleDict,
                                ['options' => [ $filter->role => ['Selected' => true]],]);
                        ?>
                        <div>
                            <?php
                            echo $form->field($filter, 'showStatusActive')->checkbox(['class' => 'showStatus']);
                            echo $form->field($filter, 'showStatusInactive')->checkbox(['class' => 'showStatus']);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 ">
                <?php
                echo $form->field($filter, 'showOnlyChecked')->checkbox();
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
                    'onclick' => 'cleanFilter();',
                ]) ?>
                <!--
                              Html::a('У файл', ['/adminxx/user/export-to-exel', 'exportQuery' => $exportQuery],
                    [
                        'class' => 'btn btn-success',
                        'data-method' => 'post',
                        'onclick' => 'preloader("show", "mainContainer", 0);'
                    ]);

                -->

                <?= Html::a('У файл', null,
                    [
                        'class' => 'btn btn-success',
                        'onclick' => "uploadDataPartitional();"
                    ]);?>
            </div>
        </div>

    </div>
    <?php ActiveForm::end(); ?>
</div>

<script>

/*
    $(".checkBoxAll").change(function() {
        if(this.checked) {
            $('.showStatus').prop('checked', false);
        }
    });
    $(".showStatus").change(function() {
        if(this.checked) {
            $('.checkBoxAll').prop('checked', false);
        }
    });
    */

</script>


