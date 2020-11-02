<?php

use yii\helpers\Html;
use \yii\widgets\Pjax;


$this->title = Yii::t('app', 'Переводы');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6" align="left">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="col-md-6" align="right" style="padding-top: 20px">
            <?php
            echo Html::a('Добавить новый', '/adminx/translation/create', [
                'class' =>'btn btn-primary',
            ]);
            ?>
        </div>

    </div>
    <div class="row">
        <?php Pjax::begin(['id' => 'gridTranslation']);
        echo \yii\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            //-------------------------------------------
            'tableOptions' => [
                'class' => 'table table-bordered table-striped ',
            ],
            //-------------------------------------------
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                'tkey',
                'category',
                'language',
                'message',
                ['class' => 'yii\grid\ActionColumn',
                    'buttons'=>[
                        ''=>function($url, $data) {
                            return Html::a('<span class="glyphicon glyphicon-pensil"></span>', ['/adminx/translation/update', 'tkey' => $data['tkey'] ],
                                [
                                    'title' => 'Изменить',
                                ]);

                        },
                        'delete'=>function($url, $data) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/adminx/translation/delete', 'tkey' => $data['tkey'] ],
                                [
                                    'title' => 'Удалить',
                                    'data-confirm' => 'Подтвердите удаление',
                                    'data-method' => 'post',
                                ]);

                        },
                    ],
                    'template'=>' {update}  {delete}',


                ],
                //------------------------------
            ],

        ]);
        Pjax::end() ?>
    </div>

</div>




