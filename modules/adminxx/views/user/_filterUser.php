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
                        echo $form->field($filter, 'email');
                        ?>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <?php
                        echo $form->field($filter, 'spec_document');
                        echo $form->field($filter, 'direction', ['inputOptions' =>
                            ['class' => 'form-control', 'tabindex' => '4']])
                            ->dropDownList(UserData::$directionArray,
                                ['options' => [ $filter->direction => ['Selected' => true]],]);
                        echo $form->field($filter, 'role', ['inputOptions' =>
                            ['class' => 'form-control', 'tabindex' => '4']])
                            ->dropDownList($filter->roleDict,
                                ['options' => [ $filter->role => ['Selected' => true]],]);
                        ?>
                        <div>
                            <?php
                            echo $form->field($filter, 'showStatusAll')->checkbox(['class' => 'checkBoxAll']);
                            echo $form->field($filter, 'showStatusActive')->checkbox(['class' => 'showStatus']);
                            echo $form->field($filter, 'showStatusInactive')->checkbox(['class' => 'showStatus']);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 ">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-11">
                                <?= $form->field($filter, 'treeDepartmentName')->textInput(['disabled' =>true])
                                    ->label('Місце роботи (аттест.)');?>
                            </div>
                            <div class="col-md-1">
                                <?= Html::a('<span class="glyphicon glyphicon-chevron-down"></span>', null, [
                                    'onclick' => 'showHideTree(this);',
                                ]);?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="selectTree" class="simpleBlock" style=" height: 50vh; width: auto; overflow: auto; display: none; ">
                                    <?= \app\widgets\tree\TreeWidget::widget([
                                        'showFilter' => false,
                                        'useConserve' => false,
                                        'noShowPositions' => 1,
                                        'treeHeight' => '40vh',
                                        'searchDepartmentId' => $filter->treeDepartment_id,
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($filter, 'job_name')->label('Місце роботи (не аттест.)');?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($filter, 'treeDepartment_id')->hiddenInput()->label(false);?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group" align="center" style="padding: 20px">
                <?= Html::submitButton('Шукати', ['class' => 'btn btn-primary', 'id' => 'subBtn']) ?>
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
    function cleanFilter(){
        $("#userfilter-username"). attr('value', '');
        $("#userfilter-last_name"). attr('value', '');
        $("#userfilter-first_name"). attr('value', '');
        $("#userfilter-middle_name"). attr('value', '');
       // $("#userfilter-role"). attr('value', '');
        $("#userfilter-job_name"). attr('value', '');
      //  $("#userfilter-direction"). attr('value', '');
        $("#userfilter-spec_document"). attr('value', '');
      //  $("#userfilter-treedepartment_id"). attr('value', 14005);

        document.getElementById('userfilter-role').value = null;
        document.getElementById('userfilter-email').value = null;
        document.getElementById('userfilter-direction').value = null;
        document.getElementById('userfilter-treedepartment_id').value = 14005;


        $('.showStatus').prop('checked', false);
        $('.checkBoxAll').prop('checked', true);
        $("#subBtn").click();
    }

    function showHideTree(item)
    {
        switch (item.innerHTML) {
            case '<span class="glyphicon glyphicon-chevron-down"></span>':
                $("#selectTree").show('slow');
                item.innerHTML = '<span class="glyphicon glyphicon-chevron-up"></span>';
                break;
            case '<span class="glyphicon glyphicon-chevron-up"></span>':
                $("#selectTree").hide('slow');
                item.innerHTML = '<span class="glyphicon glyphicon-chevron-down"></span>';
                break;


        }
     //   console.log(item.innerHTML);
    }

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

</script>


