<?php
use yii\helpers\Html;
use \yii\widgets\DetailView;
?>

<?php
echo DetailView::widget([
    'model' => $binar,
    'attributes' => [
        'id',
        'parent_id',
        'position',
        'path',
        'level',
        'name',
        /*
        [
            'label' => 'Статус по PID',
            'format' => 'html',
            'value' => function($data)
            {
                return ($data->isRunning)
                    ? "<span class='blink_text_no_active_waiting_to_active' >Працюе</span>"
                    : "<span>Не працюе</span>";
            }
        ],
        */
    ],
]);

?>
