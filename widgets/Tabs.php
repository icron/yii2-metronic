<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Tabs renders a Tab Metronic component.
 *
 * For example:
 *
 * ```php
 * echo Tabs::widget([
 *     'items' => [
 *         [
 *             'label' => 'One',
 *             'content' => 'Anim pariatur cliche...',
 *             'active' => true
 *         ],
 *         [
 *             'label' => 'Two',
 *             'content' => 'Anim pariatur cliche...',
 *             'headerOptions' => [...],
 *             'options' => ['id' => 'myveryownID'],
 *         ],
 *         [
 *             'label' => 'Dropdown',
 *             'items' => [
 *                  [
 *                      'label' => 'DropdownA',
 *                      'content' => 'DropdownA, Anim pariatur cliche...',
 *                  ],
 *                  [
 *                      'label' => 'DropdownB',
 *                      'content' => 'DropdownB, Anim pariatur cliche...',
 *                  ],
 *             ],
 *         ],
 *     ],
 * ]);
 * ```
 *
 */
class Tabs extends \yii\bootstrap\Tabs
{

    // Tab placements.
    const PLACEMENT_ABOVE = 'above';
    const PLACEMENT_BELOW = 'below';
    const PLACEMENT_LEFT = 'left';
    const PLACEMENT_RIGHT = 'right';

    // Tab type
    const NAV_TYPE_TABS = 'nav-tabs';
    const NAV_TYPE_PILLS = 'nav-pills';

    /**
     * @var string, specifies the Bootstrap tab styling.
     * Valid values 'nav-tabs',  'nav-pills'
     */
    public $navType = self::NAV_TYPE_TABS;

    /**
     * @var string the placement of the tabs.
     * Valid values are 'above', 'below', 'left' and 'right'.
     */
    public $placement = self::PLACEMENT_BELOW;

    /**
     * @var bool Indicates whether tabs is styled for Metronic.
     */
    public $styled = true;
    /**
     * @var bool Indicates whether tabs is justified.
     */
    public $justified = false;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        if ($this->justified) {
            Html::addCssClass($this->options, 'nav-justified');
        }

        Html::addCssClass($this->options, 'nav ' . $this->navType);
        parent::init();
    }

    /**
     *  Renders the widget.
     */
    public function run()
    {
        $classWrap = ['tabs-' . $this->placement];
        if ($this->styled) {
            $classWrap[] = 'tabbable-custom';
            if ($this->justified) {
                $classWrap[] = 'nav-justified';
            }
        } else {
            $classWrap[]= 'tabbable';
        }
        echo Html::beginTag('div', ['class' => implode(' ', $classWrap)]);
        parent::run();
        echo Html::endTag('div');
    }

    /**
     * Renders tab items as specified on [[items]].
     * @return string the rendering result.
     * @throws InvalidConfigException.
     */
    protected function renderItems()
    {
        $headers = [];
        $panes = [];
        foreach ($this->items as $n => $item) {
            if (!isset($item['label'])) {
                throw new InvalidConfigException("The 'label' option is required.");
            }
            $label = $this->encodeLabels ? Html::encode($item['label']) : $item['label'];
            $headerOptions = array_merge($this->headerOptions, ArrayHelper::getValue($item, 'headerOptions', []));

            if (isset($item['items'])) {
                if ($this->styled) {
                    throw new InvalidConfigException("The 'styled' not support dropdown items. Please, set 'styled' to false.");
                }
                $label .= ' <b class="caret"></b>';
                Html::addCssClass($headerOptions, 'dropdown');

                if ($this->renderDropdown($item['items'], $panes)) {
                    Html::addCssClass($headerOptions, 'active');
                }

                $header = Html::a($label, "#", ['class' => 'dropdown-toggle', 'data-toggle' => 'dropdown']) . "\n"
                    . Dropdown::widget(['items' => $item['items'], 'clientOptions' => false]);
            } elseif (isset($item['content'])) {
                $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
                $options['id'] = ArrayHelper::getValue($options, 'id', $this->options['id'] . '-tab' . $n);

                Html::addCssClass($options, 'tab-pane');
                if (ArrayHelper::remove($item, 'active')) {
                    Html::addCssClass($options, 'active');
                    Html::addCssClass($headerOptions, 'active');
                }
                $header = Html::a($label, '#' . $options['id'], ['data-toggle' => 'tab']);
                $panes[] = Html::tag('div', $item['content'], $options);
            } else {
                throw new InvalidConfigException("Either the 'content' or 'items' option must be set.");
            }

            $headers[] = Html::tag('li', $header, $headerOptions);
        }

        $headers = Html::tag('ul', implode("\n", $headers), $this->options);
        $panes = Html::tag('div', implode("\n", $panes), ['class' => 'tab-content']);

        return  ($this->placement == self::PLACEMENT_BELOW) ? ($panes . "\n" . $headers) : ($headers . "\n" . $panes);
    }
}
