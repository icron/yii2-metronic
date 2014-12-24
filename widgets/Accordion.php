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
 * Accordion renders an accordion Metronic component.
 *
 * For example:
 *
 * ```php
 * echo Accordion::widget([
 *     'items' => [
 *         [
 *             'header' => 'Item 1',
 *             'content' => 'Content 1...',
 *             // open its content by default
 *             'contentOptions' => ['class' => 'in'],
 *             'type' => Accordion::ITEM_TYPE_SUCCESS,
 *         ],
 *         [
 *             'header' => 'Item 2',
 *             'content' => 'Content 2...',
 *         ],
 *     ],
 *     'itemConfig' => ['showIcon' => true],
 *]);
 * ```
 *
 * @see http://getbootstrap.com/javascript/#collapse
 */
class Accordion extends Widget
{
    // Item types
    const ITEM_TYPE_DEFAULT = 'default';
    const ITEM_TYPE_SUCCESS = 'success';
    const ITEM_TYPE_DANGER = 'danger';
    const ITEM_TYPE_WARNING = 'warning';
    const ITEM_TYPE_INFO = 'info';

    /**
     * @var array list of groups in the collapse widget. Each array element represents a single
     * group with the following structure:
     *
     * ```php
     * [
     *     // required, the header (HTML) of the group
     *     'header' => 'Item 1',
     *     // required, the content (HTML) of the group
     *     'content' => '',
     *     // optional the HTML attributes of the content group
     *     'contentOptions' => [],
     *     // optional, the HTML attributes of the group
     *     'options' => [],
     *     // optional, the item type. Valid values are 'default', 'success', 'danger', 'warning', 'info'
     *     // Determines color of the item.
     *     'type' => '',
     * ]
     * ```
     */
    public $items = [];
    /**
     * @var array the default configuration used by item.
     */
    public $itemConfig = [];

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        Html::addCssClass($this->options, 'panel-group accordion');
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        echo Html::beginTag('div', $this->options) . "\n";
        echo $this->renderItems() . "\n";
        echo Html::endTag('div') . "\n";
        $this->registerPlugin('collapse');
    }

    /**
     * Renders collapsible items as specified on [[items]].
     * @return string the rendering result
     * @throws InvalidConfigException.
     */
    public function renderItems()
    {
        $items = [];
        $index = 0;
        foreach ($this->items as $item) {
            if (!isset($item['header'])) {
                throw new InvalidConfigException("The 'header' option is required.");
            }
            if (!isset($item['content'])) {
                throw new InvalidConfigException("The 'content' option is required.");
            }

            $options = ArrayHelper::getValue($item, 'options', []);
            $type = ArrayHelper::getValue($item, 'type', self::ITEM_TYPE_DEFAULT);
            Html::addCssClass($options, 'panel panel-' . $type);
            $items[] = Html::tag('div', $this->renderItem(array_merge($this->itemConfig, $item), ++$index), $options);
        }

        return implode("\n", $items);
    }

    /**
     * Renders a single collapsible item group
     * @param array $item a single item from [[items]]
     * @param integer $index the item index as each item group content must have an id
     * @return string the rendering result
     * @throws InvalidConfigException
     */
    protected function renderItem($item, $index)
    {
        $options = ArrayHelper::getValue($item, 'options', []);
        $type = ArrayHelper::getValue($item, 'type', self::ITEM_TYPE_DEFAULT);
        Html::addCssClass($options, 'panel panel-' . $type);
        $id = $this->options['id'] . '-collapse' . $index;
        $options = ArrayHelper::getValue($item, 'contentOptions', []);
        $options['id'] = $id;
        Html::addCssClass($options, 'panel-collapse collapse');
        $styled = '';
        if (ArrayHelper::getValue($item, 'showIcon', false)) {
            if (preg_match('/[^\w]*in[^\w]*/', $options['class'])) {
                $styled = 'accordion-toggle-styled';
            } else {
                $styled = 'accordion-toggle-styled collapsed';
            }
        }
        $headerToggle = Html::a(
                $item['header'],
                '#' . $id,
                [
                    'class' => 'accordion-toggle ' . $styled,
                    'data-toggle' => 'collapse',
                    'data-parent' => '#' . $this->options['id']
                ]
            ) . "\n";

        $header = Html::tag('h4', $headerToggle, ['class' => 'panel-title']);
        $content = Html::tag('div', $item['content'], ['class' => 'panel-body']) . "\n";

        $group = [];
        $group[] = Html::tag('div', $header, ['class' => 'panel-heading']);
        $group[] = Html::tag('div', $content, $options);

        return implode("\n", $group);
    }
}
