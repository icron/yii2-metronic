<?php
/**
 * @link http://yii2metronic.icron.org/
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use yii\bootstrap\Button as BButton;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Html;

/**
 * DropdownContent renders a group or split button dropdown content.
 *
 * For example,
 *
 * ```php
 * echo DropdownContent::widget([
 *     'label' => 'Action',
 *     'dropdown' => 'Content',
 * ]);
 * ```
 */
class DropdownContent extends ButtonDropdown
{
    /**
     * @var string the button label
     */
    public $label = 'Button';
    /**
     * @var array the HTML attributes of the button.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];
    /**
     * @var string the dropdown content.
     */
    public $dropdown = '';

    /**
     * Executes the widget.
     */
    public function run()
    {
        echo Html::beginTag('div', ['class' => 'btn-group']);
        echo $this->renderButton() . "\n" . $this->renderDropdown();
        echo Html::endTag('div');

        $this->registerPlugin('button');

    }

    /**
     * Generates the button dropdown.
     * @return string the rendering result.
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
            $splitButton = BButton::widget([
                'label' => '<span class="caret"></span>',
                'encodeLabel' => false,
                'options' => $this->options,
                'view' => $this->getView(),
            ]);
        } else {
            $label .= ' <span class="caret"></span>';
            $options = $this->options;
            if (!isset($options['href'])) {
                $options['href'] = '#';
            }
            Html::addCssClass($options, 'dropdown-toggle');
            $options['data-toggle'] = 'dropdown';
            $splitButton = '';
        }

        return BButton::widget([
            'tagName' => $this->tagName,
            'label' => $label,
            'options' => $options,
            'encodeLabel' => false,
            'view' => $this->getView(),
        ]) . "\n" . $splitButton;
    }

    /**
     * Gets dropdown content.
     * @return string the rendering result.
     */
    protected function renderDropdown()
    {
        return Html::tag('div', $this->dropdown, ['class' => 'dropdown-menu']);
    }
}
