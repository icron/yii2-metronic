<?php
/**
 * @link http://yii2metronic.icron.org/
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/**
 *  DateRangePicker renders dateRangePicker widget.
 *
 *  There are two modes of operation of the widget are 'input' and 'advance'.
 *  Mode 'input' renders input HTML element and mode 'advance' renders span HTML element.
 *  Widget renders a hidden field with the model name that this widget is associated with
 *  and the current value of the selected date.
 *
 *  For example, if [[model]] and [[attribute]] are not set:
 * ```php
 *  DateRangePicker::widget([
 *      'mode' => DateRangePicker::MODE_ADVANCE,
 *      'labelDateFormat' => 'MMMM D, YYYY',
 *      'type' => DateRangePicker::TYPE_BLUE,
 *      'clientOptions' => [
 *          'format' => 'YYYY-MM-DD',
 *          'ranges' => new \yii\web\JsExpression("{
 *              'Today': [moment(), moment()],
 *              'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
 *              'Last 7 Days': [moment().subtract('days', 6), moment()],
 *              'Last 30 Days': [moment().subtract('days', 29), moment()],
 *              'This Month': [moment().startOf('month'), moment().endOf('month')],
 *              'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
 *          }"),
 *      ],
 *      'name' => 'date',
 *      'icon' => 'fa fa-calendar',
 *      'value' => '2014-02-15 - 2014-02-18',
 * ]);
 * ```
 * @see https://github.com/dangrossman/bootstrap-daterangepicker
 */
class DateRangePicker extends InputWidget
{
    // mode
    const MODE_INPUT = 'input';
    const MODE_ADVANCE = 'advance';
    // type
    const TYPE_DEFAULT = 'default';
    const TYPE_RED = 'red';
    const TYPE_BLUE = 'blue';
    const TYPE_GREEN = 'green';
    const TYPE_YELLOW = 'yellow';
    const TYPE_PURPLE = 'purple';
    const TYPE_DARK = 'dark';
    /**
     * @var string dateRangePicker icon.
     */
    public $icon;
    /**
     * @var string date separator.
     */
    public $separator = ' - ';
    /**
     * @var string dateRangePicker format is displayed on the span HTML element in case mode 'advance'.
     * Using the format from JS dateRangePicker formats.
     */
    public $labelDateFormat;
    /**
     * @var string dateRangePicker mode.
     * Valid values are 'input', 'advance'.
     * When it is set 'input' then it will be shown input element.
     */
    public $mode = self::MODE_ADVANCE;
    /**
     * @var string dateRangePicker type.
     * Valid values are 'default', 'red', 'blue', 'green', 'yellow', 'purple', 'dark'
     * Type determines what color will have widget.
     */
    public $type = self::TYPE_DEFAULT;
    /**
     * Executes the widget.
     */
    public function run()
    {
        if (empty($this->clientOptions['format'])) {
            $this->clientOptions['format'] = 'MM/DD/YYYY';
        }

        if (empty($this->labelDateFormat)) {
            $this->labelDateFormat = $this->clientOptions['format'];
        }

        if ($this->hasModel()) {
            $hiddenInput = Html::activeHiddenInput($this->model, $this->attribute);
            $input = Html::activeTextInput($this->model, $this->attribute);
            $name = Html::getInputName($this->model, $this->attribute);
            $value = $this->model->{$this->attribute};
            $this->options['id'] .= '-picker';
        } else {
            $hiddenInput = Html::hiddenInput($this->name, $this->value);
            $input = Html::textInput($this->name, $this->value, ['class' => 'form-control']);
            $name = $this->name;
            $value = $this->value;
        }
        $arrValue = array_map('trim', ($value = explode($this->separator, $value)) ? $value : []);
        $callback = '';
        $initJS = '';
        $lines = [];
        switch ($this->mode) {
            case self::MODE_ADVANCE:
                Html::addCssClass($this->options, 'btn ' . $this->type);
                $lines[] = $hiddenInput;
                $lines[] = Html::beginTag('div', $this->options);
                if (!empty($this->icon)) {
                    $lines[] = Html::tag('i', '', ['class' => $this->icon]) .  ' ';
                }
                $lines[] = Html::tag('span', ' ');
                $lines[] = Html::tag('b', '', ['class' => 'fa fa-angle-down']);
                $lines[] = Html::endTag('div');
                $callback = "function (start, end) {
                        $('#{$this->options['id']} span')
                            .html(start.format('{$this->labelDateFormat}')
                            + '{$this->separator}' + end.format('{$this->labelDateFormat} '));
                        // set value to hidden input
                        $('input[name=\"{$name}\"]').val(start.format('{$this->clientOptions['format']}')
                            + '{$this->separator}' + end.format('{$this->clientOptions['format']}'));
                    }";
                if(count($arrValue) == 2) {
                    $initJS = ("
                      !(function($){
                        var el = $('#{$this->options['id']}');
                        el.data('daterangepicker')
                            .setStartDate(moment('{$arrValue[0]}', '{$this->clientOptions['format']}'));
                        el.data('daterangepicker')
                            .setEndDate(moment('{$arrValue[1]}', '{$this->clientOptions['format']}'));
                        el.find('span')
                            .html(moment('{$arrValue[0]}', '{$this->clientOptions['format']}').format('{$this->labelDateFormat}')
                                + '{$this->separator}'
                                + moment('{$arrValue[1]}', '{$this->clientOptions['format']}').format('{$this->labelDateFormat} '));
                       })(jQuery);
                    ");
                }
                break;
            case self::MODE_INPUT:
                Html::addCssClass($this->options, 'input-group');
                $lines[] = Html::beginTag('div', $this->options);
                $lines[] = $input;
                $lines[] = Html::beginTag('span', ['class' => 'input-group-btn']);
                $lines[] = Button::widget(
                    [
                        'label' => ' ',
                        'icon' => $this->icon,
                        'type' => $this->type,
                        'iconPosition' => Button::ICON_POSITION_RIGHT
                    ]
                );
                $lines[] = Html::endTag('span');
                $lines[] = Html::endTag('div');
                $callback = "function (start, end) {
                        $('#{$this->options['id']} input')
                            .val(start.format('{$this->clientOptions['format']}')
                            + '{$this->separator}' + end.format('{$this->clientOptions['format']} '));
                        // set value to hidden input
                        $('input[name=\"{$name}\"]').val(start.format('{$this->clientOptions['format']}')
                            + '{$this->separator}' + end.format('{$this->clientOptions['format']}'));
                    }";
                if(count($arrValue) == 2) {
                    $initJS = ("
                      !(function($){
                        var el = $('#{$this->options['id']}');
                        el.data('daterangepicker')
                            .setStartDate(moment('{$arrValue[0]}', '{$this->clientOptions['format']}'));
                        el.data('daterangepicker')
                            .setEndDate(moment('{$arrValue[1]}', '{$this->clientOptions['format']}'));
                        el.find('input')
                            .val(moment('{$arrValue[0]}', '{$this->clientOptions['format']}').format('{$this->clientOptions['format']}')
                                + '{$this->separator}'
                                + moment('{$arrValue[1]}', '{$this->clientOptions['format']}').format('{$this->clientOptions['format']} '));
                       })(jQuery);
                    ");
                }
                break;
        }
        echo implode("\n", $lines);
        DateRangePickerAsset::register($this->view);
        $this->registerPlugin('daterangepicker', $callback);
        if (!empty($initJS)) {
            $this->view->registerJs($initJS, View::POS_READY);
        }
    }

    /**
     * Registers a specific Bootstrap plugin and the related events
     * @param string $name the name of the Bootstrap plugin
     * @param string $callback second parameter option of the Bootstrap plugin
     */
    protected function registerPlugin($name, $callback) {
        $view = $this->getView();
        $id = $this->options['id'];
        if ($this->clientOptions !== false) {
            $options = empty($this->clientOptions) ? '' : Json::encode($this->clientOptions);
            if (!empty($callback)) {
                $js = "jQuery('#$id').$name($options, $callback);";
            } else {
                $js = "jQuery('#$id').$name($options);";
            }
            $view->registerJs($js);
        }
        if (!empty($this->clientEvents)) {
            $js = [];
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = "jQuery('#$id').on('$event', $handler);";
            }
            $view->registerJs(implode("\n", $js));
        }
    }
}
