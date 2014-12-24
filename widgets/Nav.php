<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Nav renders a nav HTML component.
 *
 * For example:
 *
 * ```php
 * echo Nav::widget([
 *     'items' => [
 *         [
 *             'icon' => 'fa fa-warning',
 *             'badge' => Badge::widget(['label' => 'New', 'round' => false]),
 *             'label' => 'Home',
 *             'url' => ['site/index'],
 *             'linkOptions' => [...],
 *         ],
 *         [
 *             'label' => 'Dropdown',
 *             'items' => [
 *                  ['label' => 'Level 1 - Dropdown A', 'url' => '#'],
 *                  '<li class="divider"></li>',
 *                  '<li class="dropdown-header">Dropdown Header</li>',
 *                  ['label' => 'Level 1 - Dropdown B', 'url' => '#'],
 *             ],
 *         ],
 *     ],
 * ]);
 * ```
 *
 * Note: Multilevel dropdowns beyond Level 1 are not supported in Bootstrap 3.
 */
class Nav extends \yii\bootstrap\Nav
{
    // position
    const POS_LEFT = '';
    const POS_RIGHT = 'pull-right';

    /**
     * @var array list of items in the nav widget. Each array element represents a single
     * menu item which can be either a string or an array with the following structure:
     *
     * - label: string, required, the nav item label.
     * - icon: string, optional, the nav item icon.
     * - badge: array, optional
     * - url: optional, the item's URL. Defaults to "#".
     * - visible: boolean, optional, whether this menu item is visible. Defaults to true.
     * - linkOptions: array, optional, the HTML attributes of the item's link.
     * - options: array, optional, the HTML attributes of the item container (LI).
     * - active: boolean, optional, whether the item should be on active state or not.
     * - items: array|string, optional, the configuration array for creating a [[Dropdown]] widget,
     *   or a string representing the dropdown menu. Note that Bootstrap does not support sub-dropdown menus.
     *
     * If a menu item is a string, it will be rendered directly without HTML encoding.
     */
    public $items = [];
    /**
     * @var string the nav position
     */
    public $position = self::POS_RIGHT;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        Html::addCssClass($this->options, 'navbar-nav');
        Html::addCssClass($this->options, $this->position);
        parent::init();
    }

    /**
     * Renders a widget's item.
     * @param string|array $item the item to render.
     * @return string the rendering result.
     * @throws InvalidConfigException
     */
    public function renderItem($item)
    {
        if (is_string($item)) {
            return $item;
        }
        if (!isset($item['label']) && !isset($item['icon'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }
        $type =  ArrayHelper::getValue($item, 'type', '');
        $options = ArrayHelper::getValue($item, 'options', []);
        if ($type === 'user') {
            $label = $item['label'];
            Html::addCssClass($options, 'user');
        } else {
            $label = $this->encodeLabels ? Html::encode($item['label']) : $item['label'];
        }
        $icon = ArrayHelper::getValue($item, 'icon', null);
        if ($icon) {
            $label = Html::tag('i', '', ['alt' => $label, 'class' => $icon]);
        }
        $label .=  ArrayHelper::getValue($item, 'badge', '');
        $items = ArrayHelper::getValue($item, 'items');
        $url = Url::toRoute(ArrayHelper::getValue($item, 'url', '#'));
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);

        if (isset($item['active'])) {
            $active = ArrayHelper::remove($item, 'active', false);
        } else {
            $active = $this->isItemActive($item);
        }

        if ($active) {
            Html::addCssClass($options, 'active');
        }

        if ($items !== null) {
            $linkOptions['data-toggle'] = 'dropdown';
            $linkOptions['data-hover'] = 'dropdown';
            $linkOptions['data-close-others'] = 'true';
            Html::addCssClass($options, 'dropdown');
            Html::addCssClass($linkOptions, 'dropdown-toggle');

            if (is_array($items)) {
                $items = Dropdown::widget([
                        'title' => ArrayHelper::getValue($item, 'title', ''),
                        'more' => ArrayHelper::getValue($item, 'more', []),
                        'scroller' => ArrayHelper::getValue($item, 'scroller', []),
                        'items' => $items,
                        'encodeLabels' => $this->encodeLabels,
                        'clientOptions' => false,
                        'options' => $type !== 'user' ? ['class' => 'extended'] : [],
                    ]);
            }
        }

        return Html::tag('li', Html::a($label, $url, $linkOptions) . $items, $options);
    }

    /**
     * Renders user item.
     * @param $label string User label
     * @param $photo string User photo url
     * @return string the rendering result
     */
    public static function userItem($label, $photo)
    {
        $lines = [];
        $lines[] = Html::img($photo, ['alt' => $label]);
        $lines[] = Html::tag('span', $label, ['class' => 'username']);
        $lines[] = Html::tag('i', '', ['class' => 'fa fa-angle-down']);
        return implode("\n", $lines);
    }
}