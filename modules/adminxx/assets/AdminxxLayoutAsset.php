<?php

namespace app\modules\adminxx\assets;

use yii\web\AssetBundle;

class AdminxxLayoutAsset extends  AssetBundle {
    public $baseUrl = '@web/modules/adminxx/assets';
    public $sourcePath = '@app/modules/adminxx/assets';
    public $publishOptions = ['forceCopy' => true];
    public $css = [
        'css/adminx.css',
        'css/site.css'
       // 'mdl/material.css'

    ];
    public $js = [
        'js/layout.js',
      //  'mdl/material.js',
    ];
    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}