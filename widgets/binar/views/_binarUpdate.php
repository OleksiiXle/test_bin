<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Binar;
?>

<?php $form = ActiveForm::begin(['id' => 'binarMmodifyForm']); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-11">
            <?php
            echo $form->field($binar, 'id')->label('ID родителя');
            echo $form->field($binar, 'position')
                ->dropDownList(Binar::POSITIONS, ['options' => [ $binar->position => ['Selected' => true]],]);
            ?>
        </div>
    </div>
    <div class="row" align="center">
        <div class="col-md-11">
            <?= Html::button('Сохранить',
                [
                    'id' => 'btn_' . $binar_id . '_updateForm',
                    'class' => 'btn btn-primary',
                ]); ?>
            <?= Html::button( \Yii::t('app', 'Отмена'),
                ['class' =>  'btn btn-danger',
                    'onclick' => '$("#main-modal-md").modal("hide")',
                ]) ?>
        </div>
    </div>

</div>
<?php ActiveForm::end(); ?>
