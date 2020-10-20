<?php

namespace app\widgets\backgroundTask;

use yii\base\Widget;
use yii\helpers\Json;

class BackgroundTaskWidget extends Widget
{
    public $mode = 'prod'; //-- or 'dev'
    public $title = '';
    public $startBtnId = "backgroundTaskStartBtn";
    public $startBtnClickFunction = "";
    public $checkForAlreadyRunning = false; //-если уже запущена задача с такими $model и $arguments - новая не запускается
    public $checkProgressInterval ;// - пауза в запросах о состоянии задачи (миллисекунды), дефолтно 2000
    public $urlStartBackgroundTask ;// - URL контроллера, который запускает фоновую задачу, дефолтно '/background-tasks/start-task'
    public $urlGetTaskProgress ;// - URL контроллера, который возвращает состояние фоновой задачи, дефолтно '/background-tasks/check-task'
    public $model ;// - модель, которая будет выполнять фоновую задачу (с неймспейсом)
    public $arguments ;// - строка JSON с аргументами, которые будут переданы в модель
    public $_csrf ;// - дефолтно $('meta[name="csrf-token"]').attr("content")

    public $taskStatusArea ;// - селектор области экрана, где отображается статус задачи
    public $customStatusArea ;// - селектор области экрана, где отображается пользовательский статус задачи
    public $progressArea = "progressArea" ;// - селектор области экрана, где отображается прогресс выполнения задачи
    public $resultArea  = "resultArea";// - селектор области экрана, где отображается промежуточный результат (куда дописываются куски текста)
    public $errorsArea = "errorArea";// - селектор области экрана, где отображается сообщение об ошибке (может совпадать с resultArea)
    public $progressValueArea ;// - селектор области экрана, где отображается цифровое значение прогресса выполнения задачи в процентах (для отладки)
    public $ajaxCounterArea ;// - селектор области экрана, где отображается цифровое значение счетчика запросов к серверу (для отладки)

    public $showTaskStatusArea ;// - показывать taskStatusArea, дефолтно false
    public $showCustomStatusArea ;// - показывать customStatusArea, дефолтно false
    public $showProgressArea = true;// - показывать progressArea
    public $showResultArea = false ;// - показывать resultArea, дефолтно false
    public $showErrorsArea = true;// - показывать errorsArea
    public $showProgressValueArea ;// - показывать progressValueArea, дефолтно false
    public $showAjaxCounterArea ;// - показывать ajaxCounterArea, дефолтно false

    public $showPreloader = "$('html,body').css('cursor','wait');";// - js-функция, без параметров, вызывается при старте, показывает прелоадер, можно переписывать в params
    public $hidePreloader = "$('html,body').css('cursor','default');";// - js-функция, без параметров,вызывается при окончании (успешном или по ошибке), скрывает прелоадер, можно переписывать в params
    public $setProgress;// - js-функция, параметр progress, как изменять progressArea при изменении прогресса, можно переписывать в params, progress - прогресс в процентах
    public $doOnSuccesss;// - js-функция, параметр response что делать при успешном завершении фоновой задачи
    public $doOnError;// - js-функция, параметр response, что делать при ошибке во время исполнения фоновой задачи
    public $doOnNotFound;// - js-функция, параметр response, что делать если фоновая задача не найдена (была удалена пользователем)

    public static $autoIdPrefix = '';

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $id = $this->getId();
        $view = $this->getView();
        BackgroundTaskAssets::register($view);
        $params = get_object_vars($this);
        $js = 'var params = {';
        foreach ($params as $name => $value) {
            if (!empty($value)) {
                switch ($name) {
                    case 'startSelector':
                    case 'title':
                        break;
                    case 'model':
                        $js .= PHP_EOL . "    $name : '" . addcslashes($value, '\\') . "',";
                        break;
                    case 'arguments':
                        $js .= PHP_EOL . "    $name : '" . Json::htmlEncode($value) . "',";
                        break;
                    case 'progressArea':
                    case 'errorsArea':
                    case 'taskStatusArea':
                    case 'customStatusArea':
                    case 'resultArea':
                    case 'progressValueArea':
                    case 'ajaxCounterArea':
                        $js .= PHP_EOL . "    $name : '#" . $value . '_' . $id . "',";
                    //    $js .= PHP_EOL . "    $name : $('#" . $value. "_" . $id . "'),";
                        break;
                    case 'showPreloader':
                    case 'hidePreloader':
                        $js .= PHP_EOL . "    $name : function () {{$value}},";
                        break;
                    default:
                        if (is_string($value)) {
                            $js .= PHP_EOL . "    $name : '$value',";
                        } else {
                            $js .= PHP_EOL . "    $name : $value,";
                        }
                }

            }

        }
        $js .= PHP_EOL . '};';
        $view->registerJs($js,\yii\web\View::POS_READY);
        $js = "
            var backgroundTask_$id = new BackgroundTask(params);
            backgroundTask_$id.init();

            $(document).on('click', '#$this->startBtnId', function () {
                  $('#backgroundTask_$id').css('display', 'block');
                  
                  backgroundTask_$id.model = WORKER_CLASS;
                  backgroundTask_$id.arguments = {
                        'filterModel' : FILTER_MODEL,
                        'query' : filterQuery,
                        'checkedIds' : checkedIds
                  };
                  
                  backgroundTask_$id.start();
                });
        ";
        $view->registerJs($js,\yii\web\View::POS_READY);

        return $this->render('backgroundTask',
            [
                'id' => $id,
                'title' => $this->title,
            ]);
    }
}
