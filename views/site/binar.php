<?php
use app\widgets\binar\BinarWidget;
use yii\bootstrap\Modal;

$this->title = 'Бинар';
?>
<div class="container-fluid">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
        <div class="xCard" style="min-height: 300px ">
            <?php
            echo BinarWidget::widget([
                'binar_id' => 'binar1',
                'params' => [
                    'mode' => 'update'
                ]
            ])
            ?>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="xCard" style="min-height: 300px ">
            <div id="binarInfo">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready ( function(){
       initTrees();
    });
</script>

<?php
//---- среднее окно
Modal::begin([
    'headerOptions' => ['id' => 'modalHeader_md','class'=>'text-center'],
    'id' => 'main-modal-md',
    'size' => 'modal-md',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE],
]);?>
<div id='modalContent_md'></div>
<?php Modal::end();?>

