<?php
namespace app\widgets\xlegrid;

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\PjaxAsset;

class Xlegrid extends GridView
{
    public $filterPosition = self::FILTER_POS_HEADER;
    public $filterView;// = '@app/views/dictionary/_search';
    public $gridTitle = '';
    public $additionalTitle = null;
    public $filterRenderOptions = [
        'class' => 'table table-bordered',
        'style' => 'background: none',
    ];
    public $usePjax = false;
    public $pjaxContainerId = '';
    public $pjaxEnablePushState = true;
    public $pjaxEnableReplaceState = false;
    public $pjaxTimeout = 1000;
    public $pjaxScrollTo = false;
    public $pjaxClientOptions;
    public $useCheckForRows = false;
    public $checkActionList = [
        'actions' => [
            'action1' => 'action1***',
            'action2' => 'action2***',
            'action3' => 'action3***',
        ],
        'options' => [
            'onchange' => 'actionWithChecked(this);',
            'style' => 'color:red;'
        ],
    ];

    //   public $gridId;
 //   public $urlGetGridFilterData;

    public function run()
    {
        $r=1;
        $this->getView()->registerJs("
            const FILTER_CLASS_SHORT_NAME = '" . $this->dataProvider->filterClassShortName . "';
            const USE_PJAX = " . $this->usePjax . ";
            const PJAX_CONTAINER_ID = '#" . $this->pjaxContainerId . "';
            ",\yii\web\View::POS_HEAD);
        if ($this->usePjax) {
            $this->registerPjaxScript();
        }
        XlegridAsset::register($this->getView());
        parent::run();
    }

    private function registerPjaxScript()
    {
        $clientOptions = [
            'push' => $this->pjaxEnablePushState,
            'replace' => $this->pjaxEnableReplaceState,
            'timeout' => $this->pjaxTimeout,
            'scrollTo' => $this->pjaxScrollTo,
            'container' => "#$this->pjaxContainerId",
            'fragment' =>  "#$this->pjaxContainerId",
        ];
        $options = Json::htmlEncode($clientOptions);
        $js = "
            var _pjaxClientOptions = {$options};
        ";
        $view = $this->getView();
        $view->registerJs($js,\yii\web\View::POS_HEAD);
        PjaxAsset::register($view);
        /*
        if ($this->pjaxLinkSelector !== false) {
            $linkSelector = Json::htmlEncode($this->pjaxLinkSelector !== null ? $this->pjaxLinkSelector : '#' . $this->pjaxContainerId . ' a');
            $js .= "jQuery(document).pjax($linkSelector, $options);";
        }
        */
    }

    /**
     * Renders the filter.
     * @return string the rendering result.
     */
    public function renderFiltersXle()
    {
        $r=1;
        if (isset($this->filterView) && isset($this->dataProvider->filterModel)){
            $filter = $this->dataProvider->filterModel;
            /*
            $filterButton = Html::a('<span class="glyphicon glyphicon-search"></span>', null, [
                'title' => \Yii::t('app', 'Фільтр'),
                'onclick' => 'buttonFilterShow(this);',
            ]);
            */
            $filterButton = Html::button('<span class="glyphicon glyphicon-search"></span>', [
                'title' => \Yii::t('app', 'Фільтр'),
                'onclick' => 'buttonFilterShow(this);',
            ]);

            $filterContent = '';
            if (!empty($this->dataProvider->filterModel->filterContent)){
                $filterContent = 'Фільтр: ' . $this->dataProvider->filterModel->filterContent;
            }
            if ($this->useCheckForRows) {
                $actionsWithChecked = 'No actions declared';
                if (isset($this->checkActionList['actions']) && isset($this->checkActionList['options'])) {
                    $actionsWithChecked = Html::dropDownList(null, null,$this->checkActionList['actions'], $this->checkActionList['options']);
                }
                $filterBody = '
            <tr>
                <td>
                   <div class="row">
                        <div class="col-lg-3">
                            ' . $actionsWithChecked . '
                        </div>
                        <div class="col-md-8" align="left" style="font-style: italic;">
                             <b>' . $this->gridTitle .  '</b>'
                    . ' '
                    . $filterContent .
                    '</div>
                        <div class="col-md-1" align="right">
                          ' . $filterButton . '
                        </div>
                   </div>
                   <div class="row">
                     <div class="col-md-12" style="display: none" id="filterZone">
                      ' . $this->render($this->filterView, [
                        'filter' => $filter,
                        'exportQuery' => $this->dataProvider->exportQuery,
                    ]) . '
                      </div>
                    </div>
                </td>
            </tr>
            ';
            } else {
                $filterBody = '
            <tr>
                <td>
                   <div class="row">
                        <div class="col-md-11" align="left" style="font-style: italic;">
                             <b>' . $this->gridTitle .  '</b>'
                    . ' '
                    . $filterContent .
                    '</div>
                        <div class="col-md-1" align="right">
                          ' . $filterButton . '
                        </div>
                   </div>
                   <div class="row">
                     <div class="col-md-12" style="display: none" id="filterZone">
                      ' . $this->render($this->filterView, [
                        'filter' => $filter,
                        'exportQuery' => $this->dataProvider->exportQuery,
                    ]) . '
                      </div>
                    </div>
                </td>
            </tr>
            ';
            }

        } else {
            $filterBody ='
            <tr>
                 <td>
                     <div class="row">
                         <div class="col-md-6">
                           <b>' . $this->gridTitle .  '</b>
                         </div>
                     </div>
                </td>
            </tr>
        ';

        }
        return $filterBody;
    }

    /**
     * Renders the table body.
     * @return string the rendering result.
     */
    public function renderTableBody()
    {
        $models = array_values($this->dataProvider->getModels());
        $keys = $this->dataProvider->getKeys();
        $rows = [];
        foreach ($models as $index => $model) {
            $key = $keys[$index];
            if ($this->beforeRow !== null) {
                $row = call_user_func($this->beforeRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }
            $rows[] = $this->renderTableRow($model, $key, $index);

            if ($this->afterRow !== null) {
                $row = call_user_func($this->afterRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }
        }
        //-- TODO new
        $cJSON = \Yii::$app->conservation
            ->setConserveGridDB($this->dataProvider->conserveName, $this->dataProvider->pagination->pageParam, $this->dataProvider->pagination->getPage());
        $cJSON = \Yii::$app->conservation
            ->setConserveGridDB($this->dataProvider->conserveName, $this->dataProvider->pagination->pageSizeParam, $this->dataProvider->pagination->getPageSize());
        //-- TODO new
        if (empty($rows) && $this->emptyText !== false) {
            $colspan = count($this->columns);

            return "<tbody>\n<tr><td colspan=\"$colspan\">" . $this->renderEmpty() . "</td></tr>\n</tbody>";
        } else {
            return "<tbody>\n" . implode("\n", $rows) . "\n</tbody>";
        }
    }

    /**
     * Renders a table row with the given data model and key.
     * @param mixed $model the data model to be rendered
     * @param mixed $key the key associated with the data model
     * @param int $index the zero-based index of the data model among the model array returned by [[dataProvider]].
     * @return string the rendering result
     */
    public function renderTableRow($model, $key, $index)
    {
        $cells = [];
        /* @var $column Column */
        if ($this->dataProvider->searchId > 0 && $key == $this->dataProvider->searchId){
            foreach ($this->columns as $column) {
                $buf= $column->contentOptions;
                $column->contentOptions['class'] = 'blink-text';
                $cells[] = $column->renderDataCell($model, $key, $index);
                $column->contentOptions = $buf;
            }
        } else {
            foreach ($this->columns as $column) {
                if ($this->useCheckForRows) {
                    if (isset($column->options['class']) && $column->options['class'] == 'row-check'){
                        $cells[] = $this->renderRowCheckBox($key);
                    } else {
                        $cells[] = $column->renderDataCell($model, $key, $index);
                    }
                } else {
                    $cells[] = $column->renderDataCell($model, $key, $index);
                }

            }

        }

        if ($this->rowOptions instanceof Closure) {
            $options = call_user_func($this->rowOptions, $model, $key, $index, $this);
        } else {
            $options = $this->rowOptions;
        }
        $options['data-key'] = is_array($key) ? json_encode($key) : (string) $key;

        return Html::tag('tr', implode('', $cells), $options);
    }

    public function renderTableHeaderXle()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $cells[] = $column->renderHeaderCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);

        return "<thead>\n" . $content . "\n</thead>";
    }

    public function renderItems()
    {
        $filter = $this->renderFiltersXle();
        $caption = $this->renderCaption();
        $columnGroup = $this->renderColumnGroup();
        $tableHeader = $this->showHeader ? $this->renderTableHeaderXle() : false;
        $tableBody = $this->renderTableBody();

        $tableFooter = false;
        $tableFooterAfterBody = false;

        if ($this->showFooter) {
            if ($this->placeFooterAfterBody) {
                $tableFooterAfterBody = $this->renderTableFooter();
            } else {
                $tableFooter = $this->renderTableFooter();
            }
        }

        $content = array_filter([
            $caption,
            $columnGroup,
            $tableHeader,
            $tableFooter,
            $tableBody,
            $tableFooterAfterBody,
        ]);
        $filterRenderOptions = [
            'class' => 'table table-bordered',
            'style' => 'background: none',
        ];
        if (isset($this->tableOptions['class'])){
            $this->filterRenderOptions['class'] = str_replace('table-striped', '', $this->tableOptions['class']);
        }
        if (isset($this->tableOptions['style'])){
            $this->filterRenderOptions['style'] .= ';' .$this->tableOptions['style'];
        }

        $ret = Html::tag('table', $filter, $filterRenderOptions)
            . Html::tag('table', implode("\n", $content), $this->tableOptions);

        return $ret;
    }

    public function renderRowCheckBox($key)
    {
        $checkBox = '<input type="checkbox" id="row-check-"' .  $key . '" class="row-check" data-id = "' . $key . '" onChange="checkRow(this);" >';
        return Html::tag('td', $checkBox);
    }



    public function renderFilters___()
    {
        $r=1;
        if (isset($this->filterView) && isset($this->dataProvider->filterModel)){
            $filter = $this->dataProvider->filterModel;
            $filterButton = Html::a('<span class="glyphicon glyphicon-search"></span>', null, [
                'title' => \Yii::t('app', 'Фільтр'),
                'onclick' => 'buttonFilterShow(this);',
            ]);
            $filterBody ='
        <td colspan='. count($this->columns) . '>
        <div class="row">
             <div class="col-md-6">
                    <b>' . $this->gridTitle .  '</b>'
                . ' ' . (isset($this->dataProvider->filterModel->additionalTitle) ? $this->dataProvider->filterModel->additionalTitle : '') .
                '</div>
            <div class="col-md-6" align="right">
                    ' . $filterButton . '
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="display: none" id="filterZone">
                    ' . $this->render($this->filterView, [
                    'filter' => $filter,
                ]) . '
            </div>
        </div>
        </td>

        ';
        } else {
            $filterBody ='
        <td colspan='. count($this->columns) . '>
        <div class="row">
             <div class="col-md-6">
                    <b>' . $this->gridTitle .  '</b>
            </div>
        </div>
        </td>

        ';

        }
        return $filterBody;
    }

    /**
     * Renders the table header.
     * @return string the rendering result.
     */
    public function renderTableHeader___()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $cells[] = $column->renderHeaderCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);
        if ($this->filterPosition === self::FILTER_POS_HEADER) {
            $content = $this->renderFilters() . $content;
        } elseif ($this->filterPosition === self::FILTER_POS_BODY) {
            $content .= $this->renderFilters();
        }

        return "<thead>\n" . $content . "\n</thead>";
    }
}