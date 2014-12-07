<?php
/**
 * @link http://yii2metronic.icron.org/
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\Html;

/**
 * Select2 renders Select2 component.
 *
 * For example:
 * ```php
 * echo Select2::widget([
 *     'name' => 'select',
 *     'data' => ['1' => 'Item 1', '2' => 'Item 2'],
 *     'multiple' => true,
 * ]);
 * ```
 *
 * @see http://ivaynberg.github.io/select2/
 */
class Select2 extends InputWidget
{
    /**
     * @var bool indicates whether to display a dropdown select box or use it for tagging
     */
    public $asDropdownList = true;
    /**
     * @var bool indicates whether the select2 is disabled or not.
     */
    public $disabled = false;
    /**
     * @var array the option data items. The array keys are option values, and the array values
     * are the corresponding option labels. The array can also be nested (i.e. some array values are arrays too).
     * For each sub-array, an option group will be generated whose label is the key associated with the sub-array.
     * If you have a list of data models, you may convert them into the format described above using
     * [[\yii\helpers\ArrayHelper::map()]].
     */
    public $data = [];
    /**
     * @var bool indicates whether the select2 is multiple or not.
     */
    public $multiple = false;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        Html::addCssClass($this->options, 'form-control');
        if ($this->multiple) {
            $this->options['multiple'] = 'multiple';
        }
        if ($this->disabled) {
            $this->options['disabled'] = true;
        }
    }

    /**
     * Executes the widget.
     */
    public function run()
    {
        if ($this->hasModel()) {
            if ($this->asDropdownList) {
                echo Html::activeDropDownList($this->model, $this->attribute, $this->data, $this->options);
            } else {
                if (!isset($this->clientOptions['query']) && !isset($this->clientOptions['ajax']) && !isset($this->clientOptions['data'])) {
                    throw new InvalidConfigException('Must be set at least one of the client options: query, data, ajax.');
                }
                echo Html::activeHiddenInput($this->model, $this->attribute, $this->options);
            }
        } else {
            if ($this->asDropdownList) {
                echo Html::dropDownList($this->name, $this->value, $this->data, $this->options);
            } else {
                if (!isset($this->clientOptions['query']) && !isset($this->clientOptions['ajax']) && !isset($this->clientOptions['data'])) {
                    throw new InvalidConfigException('Must be set at least one of the options Select2: query, data, ajax.');
                }
                echo Html::hiddenInput($this->name, $this->value, $this->options);
            }
        }
        Select2Asset::register($this->view);
        $this->registerPlugin('select2');
    }
}