<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * ButtonDropdown renders a group or split button dropdown metronic component.
 *
 * For example,
 *
 * ```php
 * // a button group using Dropdown widget
 * echo ButtonDropdown::widget([
 *     'label' => 'Action',
 *     'button' => [
 *         'icon' => 'fa fa-bookmark-o',
 *         'iconPosition' => Button::ICON_POSITION_LEFT,
 *         'size' => Button::SIZE_SMALL,
 *         'disabled' => false,
 *         'block' => false,
 *         'type' => Button::TYPE_M_BLUE,
 *      ],
 *     'dropdown' => [
 *         'items' => [
 *             ['label' => 'DropdownA', 'url' => '/'],
 *             ['label' => 'DropdownB', 'url' => '#'],
 *         ],
 *     ],
 * ]);
 * ```
 *
 **/
class ButtonDropdown extends \yii\bootstrap\ButtonDropdown
{
    /**
     * @var array The configuration array for [[Button]].
     */
    public $button = [];
    /**
     * Renders the widget.
     */
    public function run()
    {
        echo Html::tag('li', $this->renderButton() . "\n" . $this->renderDropdown(), ['class' => 'btn-group']);
    }

    /**
     * Renders the button.
     * @return string the rendering result
     */
    protected function renderButton()
    {
        Html::addCssClass($this->options, 'btn');
        $label = $this->label;
        if ($this->encodeLabel) {
            $label = Html::encode($label);
        }
        if ($this->split) {
            $options = $this->options;
            $this->options['data-toggle'] = 'dropdown';
            Html::addCssClass($this->options, 'dropdown-toggle');
            $splitButton = Button::widget([
                    'label' => '<i class="fa fa-angle-down"></i>',
                    'encodeLabel' => false,
                    'options' => $this->button,
                ]);
        } else {
            $label .= ' <i class="fa fa-angle-down"></i>';
            $options = $this->options;
            if (!isset($options['href'])) {
                $options['href'] = '#';
            }
            Html::addCssClass($options, 'dropdown-toggle');
            $options['data-toggle'] = 'dropdown';
            $splitButton = '';
        }

        return Button::widget(ArrayHelper::merge($this->button, [
                'tagName' => $this->tagName,
                'label' => $label,
                'options' => $options,
                'encodeLabel' => false,
            ])) . "\n" . $splitButton;
    }

    /**
     * Renders the dropdown
     * @return string the rendering result
     */
    protected function renderDropdown()
    {
        $config = $this->dropdown;
        $config['clientOptions'] = false;
        return Dropdown::widget($config);
    }
}