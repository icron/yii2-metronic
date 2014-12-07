<?php
/**
 * @link http://yii2metronic.icron.org/
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use Yii;
use yii\helpers\Html;

/**
 * ButtonGroup renders a button group metronic component.
 *
 * For example,
 *
 * ```php
 * // a button group vertically stacked with items configuration
 * echo ButtonGroup::widget([
 *     'vertical' => true,
 *     'buttons' => [
 *         ['label' => 'A'],
 *         ['label' => 'B'],
 *     ]
 * ]);
 *
 * // a button group with an item as a string
 * echo ButtonGroup::widget([
 *     'buttons' => [
 *         Button::widget(['label' => 'A']),
 *         ['label' => 'B'],
 *     ]
 * ]);
 * ```
 */
class ButtonGroup extends \yii\bootstrap\ButtonGroup
{
    /**
     * @var bool Indicates whether the button group appears vertically stacked.
     */
    public $stacked = false;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        if ($this->stacked === true) {
            Html::addCssClass($this->options, 'btn-group-vertical');
        } else {
            Html::addCssClass($this->options, 'btn-group');
        }
    }
}