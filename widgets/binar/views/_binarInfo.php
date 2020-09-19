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
        [
            'label' => 'Все родители',
            'format' => 'html',
            'value' => function($data)
            {
                return $data->showAllParents();
            }
        ],
        [
            'label' => 'Все потомки',
            'format' => 'html',
            'value' => function($data)
            {
                return $data->showAllChildren();
            }
        ],
    ],
]);

?>
