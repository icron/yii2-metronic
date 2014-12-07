<?php
/**
 * @link http://yii2metronic.icron.org/
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use yii\helpers\Html;


/**
 * MultiSelect renders MultiSelect component.
 *
 * For example:
 * ```php
 * echo MultiSelect::widget([
 *     'name' => 'select1',
 *     'data' => ['1' => 'Item 1', '2' => 'Item 2'],
 * ]);
 * ```
 *
 * @see http://loudev.com/
 */
class MultiSelect extends InputWidget
{
    /**
     * @var bool indicates whether the multiSelect is disabled or not.
     */
    public $disabled;
    /**
     * @var array the option data items. The array keys are option values, and the array values
     * are the corresponding option labels. The array can also be nested (i.e. some array values are arrays too).
     * For each sub-array, an option group will be generated whose label is the key associated with the sub-array.
     * If you have a list of data models, you may convert them into the format described above using
     * [[\yii\helpers\ArrayHelper::map()]].
     */
    public $data = [];

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        $this->options['multiple'] = true;
        if ($this->disabled) {
            $this->options['disabled'] = true;
        }
        Html::addCssClass($this->options, 'multi-select');
    }
    /**
     * Executes the widget.
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeDropDownList($this->model, $this->attribute, $this->data, $this->options);
        } else {
            echo Html::dropDownList($this->name, $this->value, $this->data, $this->options);
        }
        MultiSelectAsset::register($this->view);
        $this->registerPlugin('multiSelect');
    }


}