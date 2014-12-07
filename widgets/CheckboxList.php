<?php
/**
 * DateRangePickerAsset for dateRangePicker widget.
 */

namespace icron\metronic\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\jui\Sortable;
use yii\web\JsExpression;
use yii\web\View;

/**
 * CheckboxList renders sortable ckeckbox list.
 * Items can not belong to the model.
 * For example,
 * ```php
 * echo CheckboxList::widget([
 *     'items' => [
 *          'full_name',
 *          [
 *              'name' => 'city',
 *              'label' => 'Location',
 *          ]
 *     ],
 *     'model' => $filterModel,
 *     'attribute' => 'columns',
 * ]);
 * ```
 */
class CheckboxList extends InputWidget
{
    /**
     * @var array list of checkbox items.
     * Each item must be either as string like the name checkbox
     * or as array with following special options:
     *  - name, required, item name
     *  - label, optional, item label
     * Item can not belong to the model.
     */
    public $items = [];
    /**
     * @var string the model attribute that this widget is associated with.
     * This model attribute contains list of checkboxes name, separated $separator value.
     */
    public $attribute;
    /**
     * @var string separator values
     */
    public $separator = ',';
    /**
     * @var array the HTML attributes for items.
     */
    public $itemOptions = [];
    /**
     * @var array items name, that will be checked.
     */
    private $_checked = [];

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        $items = [];
        if ($this->hasModel()) {
            $this->_checked = array_map('trim', explode(',', $this->model->{$this->attribute}));
            foreach ($this->items as $item) {
                $input = [];
                if (is_string($item)) {
                    $input['name'] = $item;
                    $input['label'] = $this->model->getAttributeLabel($item);
                } else {
                    if (!isset($item['name'])) {
                        throw new InvalidConfigException('Option "name" is required.');
                    }
                    $input['name'] = $item['name'];
                    $input['label'] = isset($item['label']) ? $item['label'] : $this->model->getAttributeLabel(
                        $item['name']
                    );
                }
                $items[] = $input;
            }
        } else {
            $this->_checked = array_map('trim', explode(',', $this->value));
            foreach ($this->items as $item) {
                $input = [];
                if (is_string($item)) {
                    $input['name'] = $item;
                    $input['label'] = Inflector::camel2words($item);
                } else {
                    if (!isset($item['name'])) {
                        throw new InvalidConfigException('Option "name" is required.');
                    }
                    $input['name'] = $item['name'];
                    $input['label'] = isset($item['label']) ? $item['label'] : Inflector::camel2words($item['name']);
                }
                $items[] = $input;
            }
        }

        $this->items = $items;
    }

    /**
     * Executes the widget.
     */
    public function run()
    {
        if ($this->hasModel()) {
            $hiddenInput = Html::activeHiddenInput($this->model, $this->attribute);
            $inputId = Html::getInputId($this->model, $this->attribute);
        } else {
            $hiddenInput = Html::textInput($this->name, $this->name);
            $inputId = $this->name;
        }

        $items = [];
        usort($this->items, function($a, $b){
                $aKey = array_search($a['name'], $this->_checked);
                $bKey = array_search($b['name'], $this->_checked);
                if ($aKey == $bKey) {
                    return 0;
                }

                return  ($aKey > $bKey) ? 1 : -1;
            });
        Html::addCssClass($this->itemOptions, 'btn btn-xs default');
        foreach ($this->items as $item) {
            $checkbox = Html::checkbox($item['name'], in_array($item['name'], $this->_checked));
            $items[] = Html::tag('span', $checkbox . ' ' . $item['label'], $this->itemOptions);
        }

        echo Html::beginTag('div', $this->options);
        echo Sortable::widget([
                'items' => $items,
                'options' => ['tag' => 'div'],
                'itemOptions' => ['tag' => 'span'],
                'clientOptions' => ['cursor' => 'move',
                    'start' => new JsExpression('function(e, ui){
                        ui.placeholder.height(ui.item.height());
                        ui.placeholder.width(ui.item.width());
                    }'),
                    'update' => new JsExpression("function(e, ui){
                        var values = $.map($('#{$this->options['id']} input:checkbox:checked'),
                            function(item){ return $(item).attr('name')});
                        $('#{$inputId}').val(values.join('{$this->separator}'));
                    }"),
                ],
            ]);
        echo $hiddenInput;
        echo Html::endTag('div');
       $this->registerJs($inputId);
    }

    protected function registerJs($inputId)
    {
        $this->view->registerJs("
           ;(function($){
                $('#{$this->options['id']}').on('change', 'input:checkbox', function(){
                    var values = $.map($('#{$this->options['id']} input:checkbox:checked'),
                        function(item){ return $(item).attr('name')});
                    $('#{$inputId}').val(values.join('{$this->separator}'));
                });
            })(jQuery);
        ", View::POS_READY);
    }
}