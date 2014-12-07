<?php
/**
 * @link http://yii2metronic.icron.org/
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use yii\helpers\Html;

/**
 * Spinner renders an spinner Fuel UX widget.
 *
 * For example:
 *
 * ```php
 * echo Spinner::widget([
 *     'model' => $model,
 *     'attribute' => 'country',
 *     'size' => Spinner::SIZE_SMALL,
 *     'buttonsLocation' => Spinner::BUTTONS_LOCATION_VERTICAL,
 *     'clientOptions' => ['step' => 2],
 *     'clientEvents' => ['changed' => 'function(event, value){ console.log(value);}'],
 * ]);
 * ```
 *
 * The following example will use the name property instead:
 *
 * ```php
 * echo Spinner::widget([
 *     'name'  => 'country',
 *     'clientOptions' => ['step' => 2],
 * ]);
 *```
 *
 * @see http://exacttarget.github.io/fuelux/#spinner
 * @author
 */
class Spinner extends InputWidget
{
    // position
    const BUTTONS_LOCATION_VERTICAL = 'vertical';
    const BUTTONS_LOCATION_SIDES = 'sides';
    // size
    const SIZE_XSMALL = 'xsmall';
    const SIZE_SMALL = 'small';
    const SIZE_MEDIUM = 'medium';
    const SIZE_LARGE = 'large';
    /**
     * @var string Spinner size
     */
    public $size = self::SIZE_SMALL;
    /**
     * @var array the configuration array for [[Button]].
     */
    public $buttonsConfig = ['type' => Button::TYPE_M_BLUE];
    /**
     * @var array the HTML attributes for the input element.
     */
    public $inputOptions = [];
    /**
     * @var string The buttons location.
     * Valid values are 'vertical', 'sides'
     */
    public $buttonsLocation = self::BUTTONS_LOCATION_VERTICAL;
    /**
     * Executes the widget.
     */
    public function run()
    {
        Html::addCssClass($this->inputOptions, 'spinner-input form-control');
        $this->buttonsConfig = array_merge(['label' => ''], $this->buttonsConfig);
        if ($this->hasModel()) {
            $input = Html::activeTextInput($this->model, $this->attribute, $this->inputOptions);
        } else {
            $input = Html::textInput($this->name, $this->value, $this->inputOptions);
        }
        if ($this->buttonsLocation == self::BUTTONS_LOCATION_VERTICAL) {
            $this->buttonsConfig = array_merge($this->buttonsConfig, ['size' => Button::SIZE_MINI]);
        }
        $btnUp = Button::widget(
            array_merge($this->buttonsConfig, [
                    'icon' => 'fa fa-angle-up',
                    'options' => ['class' => 'spinner-up']
                ])
        );
        $btnDown = Button::widget(
            array_merge($this->buttonsConfig, [
                    'icon' => 'fa fa-angle-down',
                    'options' => ['class' => 'spinner-down']
                ])
        );

        if ($this->buttonsLocation == self::BUTTONS_LOCATION_VERTICAL) {
            $spinner = $input . Html::tag('div', $btnUp . $btnDown, ['class' => 'spinner-buttons input-group-btn btn-group-vertical']);
        } else {
            $spinner = Html::tag('div', $btnUp . $input . $btnDown, ['class' => 'spinner-buttons input-group-btn']);
        }
        echo Html::tag('div' , Html::tag('div', $spinner, ['class' => 'input-group input-' . $this->size]), $this->options);
        SpinnerAsset::register($this->view);
        $this->registerPlugin('spinner');
    }
}
