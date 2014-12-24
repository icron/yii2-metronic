<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/**
 *  IonRangeSlider renders ionRangeSlider widget.
 *
 *  For example, if [[model]] and [[attribute]] are not set:
 * ```php
 * echo IonRangeSlider::widget([
 *     'name' => 'ionRangeSlider',
 *     'clientOptions' => [
 *         'min' => 0,
 *         'max' => 5000,
 *         'from' => 1000, // default value
 *         'to' => 4000, // default value
 *         'type' => 'double',
 *         'step' => 1,
 *         'prefix' => "$",
 *         'prettify' => false,
 *         'hasGrid' => true
 *     ],
 * ]);
 * ```
 * @see https://github.com/IonDen/ion.rangeSlider
 */
class IonRangeSlider extends InputWidget
{
    /**
     * @var string separator values
     */
    public $separator = ';';

    /**
     * Executes the widget.
     */
    public function run()
    {
        if ($this->hasModel()) {
            $values = explode($this->separator, $this->model->{$this->attribute});
            if (count($values) == 2) {
                $this->clientOptions['from'] = (int)$values[0];
                $this->clientOptions['to'] = (int)$values[1];
            }
            echo Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            $values = explode($this->separator, $this->value);
            if (count($values) == 2) {
                $this->clientOptions['from'] = (int)$values[0];
                $this->clientOptions['to'] = (int)$values[1];
            }
            echo Html::textInput($this->name, $this->value, $this->options);
        }
        IonRangeSliderAsset::register($this->view);
        $this->registerPlugin('ionRangeSlider');
    }

}
