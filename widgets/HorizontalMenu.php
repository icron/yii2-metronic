<?php
/**
 * @link http://yii2metronic.icron.org/
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */


namespace icron\metronic\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;
use yii\widgets\Menu;
use yii\widgets\ActiveForm as CoreActiveForm;

/**
 * Horizontal Menu displays a multi-level menu using nested HTML lists.
 *
 * The main property of Menu is [[items]], which specifies the possible items in the menu.
 * A menu item can contain sub-items which specify the sub-menu under that menu item.
 *
 * Menu checks the current route and request parameters to toggle certain menu items
 * with active state.
 *
 * Note that Menu only renders the HTML tags about the menu. It does do any styling.
 * You are responsible to provide CSS styles to make it look like a real menu.
 *
 * Supports multiple operating modes: classic, mega, and full mega(see [[HorizontalMenu::type]]).
 *
 * The following example shows how to use Menu:
 *
 * ```php
 * // Classic menu with search box
 * echo HorizontalMenu::widget([
 *     'items' => [
 *         // Important: you need to specify url as 'controller/action',
 *         // not just as 'controller' even if default action is used.
 *         ['label' => 'Home', 'url' => ['site/index']],
 *         // 'Products' menu item will be selected as long as the route is 'product/index'
 *         ['label' => 'Products', 'url' => ['product/index'], 'items' => [
 *             ['label' => 'New Arrivals', 'url' => ['product/index', 'tag' => 'new']],
 *             ['label' => 'Most Popular', 'url' => ['product/index', 'tag' => 'popular']],
 *         ]],
 *         ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
 *     ],
 *     'search' => [
 *         // required, whether search box is visible. Defaults to 'true'.
 *         'visible' => true,
 *         // optional, the configuration array for [[ActiveForm]].
 *         'form' => [],
 *         // optional, input options with default values
 *         'input' => [
 *             'name' => 'search',
 *             'value' => '',
 *             'options' => [
 *             'placeholder' => 'Search...',
 *         ]
 *     ],
 * ]);
 *
 * // Mega Menu without search box
 * echo HorizontalMenu::widget([
 *     'items' => [
 *         ['label' => 'Home', 'url' => ['site/index']],
 *         [
 *             'label' => 'Mega Menu',
 *             'type' => HorizontalMenu::ITEM_TYPE_FULL_MEGA,
 *              //optional, HTML text for last column
 *             'text' => 'Other HTML text',
 *             'items' => [
 *                 [
 *                     'label' => 'Column 1', // First column title
 *                     'items' => [
 *                         ['label' => 'Column 1 Item 1'],
 *                         ['label' => 'Column 1 Item 2'],
 *                     ]
 *                 ],
 *                 [
 *                     'label' => 'Column 2', // Second column title
 *                     'items' => [
 *                         ['label' => 'Column 2 Item 1'],
 *                         ['label' => 'Column 2 Item 2'],
 *                     ]
 *                 ],
 *             ]
 *         ],
 *         ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
 *     ],
 * ]);
 * ```
 *
 */
class HorizontalMenu extends Menu
{
    const ITEM_TYPE_CLASSIC = 'classic-menu-dropdown';
    const ITEM_TYPE_MEGA = 'mega-menu-dropdown';
    const ITEM_TYPE_FULL_MEGA = 'mega-menu-dropdown mega-menu-full';
    /**
     * @var string the template used to render the body of a menu which is a link.
     * In this template, the token `{url}` will be replaced with the corresponding link URL;
     * while `{label}` will be replaced with the link text.
     * This property will be overridden by the `template` option set in individual menu items via [[items]].
     */
    public $linkTemplate = '<a href="{url}">{icon}{label}</a>';
    /**
     * @var boolean whether to activate parent menu items when one of the corresponding child menu items is active.
     * The activated parent menu items will also have its CSS classes appended with [[activeCssClass]].
     */
    public $activateParents = true;
    /**
     * @var array Search options
     * is an array of the following structure:
     * ```php
     * [
     *   // required, whether search box is visible
     *   'visible' => true,
     *   // optional, ActiveForm options
     *   'form' => [],
     *   // optional, input options with default values
     *   'input' => [
     *     'name' => 'search',
     *     'value' => '',
     *     'options' => [
     *       'placeholder' => 'Search...',
     *     ]
     *   ],
     * ]
     * ```
     */
    public $search = ['visible' => true];
    /**
     * @var string the template used to render a list of sub-menus.
     * In this template, the token `{items}` will be replaced with the renderer sub-menu items.
     */
    public $submenuTemplate = "\n<ul class='{class}'>\n{items}\n</ul>\n";
    /**
     * @var string the template used to render the body of a dropdown which is a link.
     * In this template, the token `{label}` will be replaced with the link text;
     * the token `{url}` will be replaced with the corresponding link URL;
     */
    public $dropdownLinkTemplate = '<a data-toggle="dropdown" data-hover="dropdown" data-close-others="true" class="dropdown-toggle" href="{url}">{label}</a>';
    /**
     * @var string the template used to render the body of a dropdown which is a link for the Mega Menu.
     * In this template, the token `{label}` will be replaced with the link text;
     * the token `{url}` will be replaced with the corresponding link URL;
     */
    public $dropdownLinkMegaTemplate = '<a data-hover="dropdown" data-close-others="true" class="dropdown-toggle" href="{url}">{label}</a>';

    /**
     * Renders the menu.
     */
    public function run()
    {
        Html::addCssClass($this->options, 'nav navbar-nav');
        echo Html::beginTag('div', ['class' => 'hor-menu hidden-sm hidden-xs']);
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = $_GET;
        }
        $items = $this->normalizeItems($this->items, $hasActiveChild);
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'ul');
        $data = [$this->renderItems($items)];
        if (!isset($this->search['visible'])) {
            throw new InvalidConfigException("The 'visible' option of search is required.");
        }
        $data[] = Html::tag('li', $this->renderSearch());
        echo Html::tag($tag, implode("\n", $data), $options);
        echo Html::endTag('div');
    }

    /**
     * Recursively renders the menu items (without the container tag).
     * @param array $items the menu items to be rendered recursively
     * @param integer $level Indicates the level of the menu items
     * @param integer $type Item type. Valid values are:
     *  HorizontalMenu::ITEM_TYPE_CLASSIC,
     *  HorizontalMenu::ITEM_TYPE_MEGA,
     *  HorizontalMenu::ITEM_TYPE_FULL_MEGA
     * @return string the rendering result
     */
    protected function renderItems($items, $level = 1, $type = null)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $itemType = ($type === null) ? ArrayHelper::getValue($item, 'type', self::ITEM_TYPE_CLASSIC) : $type;
            $class = [];
            if ($item['active']) {
                $class[] = $this->activeCssClass;
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }
            if (!empty($class)) {
                if (empty($options['class'])) {
                    $options['class'] = implode(' ', $class);
                } else {
                    $options['class'] .= ' ' . implode(' ', $class);
                }
            }
            if ($level == 1) {
                Html::addCssClass($options, $itemType);
                $item['template'] = ($itemType == self::ITEM_TYPE_CLASSIC)
                    ? $this->dropdownLinkTemplate : $this->dropdownLinkMegaTemplate;
                if ($item['active']) {
                    $item['label'] = Html::tag('span', '', ['class' => 'selected']) . $item['label'];
                }
                if (!empty($item['items'])) {
                    $item['label'] .= ' ' . Html::tag('i', '',['class' => 'fa fa-angle-down']);
                }
            } else {
                if (!empty($item['items'])) {
                    Html::addCssClass($options, 'dropdown-submenu');
                }
            }
            if ($itemType == self::ITEM_TYPE_CLASSIC) {
                $menu = $this->renderItem($item);
                if (!empty($item['items'])) {
                    $menu .= strtr($this->submenuTemplate, [
                            '{items}' => $this->renderItems($item['items'], $level + 1, $itemType),
                            '{class}' => 'dropdown-menu',
                        ]);
                }
            } else {
                if ($level == 1) {
                    $menu = $this->renderItem($item);
                    if (!empty($item['items'])) {
                        $submenu = $this->renderItems($item['items'], $level + 1, $itemType);
                        if ($itemType == self::ITEM_TYPE_FULL_MEGA) {
                            $text = ArrayHelper::getValue($item, 'text', '');
                            $submenu = Html::tag('div', $submenu, ['class' => 'col-md-7']);
                            $submenu .= Html::tag('div', $text, ['class' => 'col-md-5']);
                        }
                        $submenu = Html::tag('div', $submenu, ['class' => 'row']);
                        $submenu = Html::tag('div', $submenu, ['class' => 'mega-menu-content']);
                        $submenu = Html::tag('li', $submenu);
                        $menu .= strtr($this->submenuTemplate, [
                                '{items}' => $submenu,
                                '{class}' => 'dropdown-menu',
                            ]);
                    }
                } else {
                    if (!empty($item['items'])) {
                        $headerItem = $item;
                        unset($headerItem['items']);
                        $headerItem['template'] = '<h3>{label}</h3>';
                        array_unshift($item['items'], $headerItem);
                        $lines[] = strtr($this->submenuTemplate, [
                                '{items}' => $this->renderItems($item['items'], $level + 1, $itemType),
                                '{class}' => 'col-md-4 mega-menu-submenu',
                            ]);
                        continue;
                    } else {
                        $item['icon'] = 'fa fa-angle-right';
                        $menu = $this->renderItem($item);
                    }
                }
            }
            $lines[] = Html::tag($tag, $menu, $options);
        }

        return implode("\n", $lines);
    }

    /**
     * Renders the content of a menu item.
     * Note that the container and the sub-menus are not rendered here.
     * @param array $item the menu item to be rendered. Please refer to [[items]] to see what data might be in the item.
     * @return string the rendering result
     */
    protected function renderItem($item)
    {
        $item['url'] =  ArrayHelper::getValue($item, 'url', '#');
        $item['label'] =  ArrayHelper::getValue($item, 'badge', '') . $item['label'];
        $item['icon'] =  ArrayHelper::getValue($item, 'icon', '');
        if ($item['icon']) {
            $item['icon'] = Html::tag('i', '', ['class' => $item['icon']]);
        }
        $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);

        return strtr($template, [
                '{url}' => Url::toRoute($item['url']),
                '{label}' => $item['label'],
                '{icon}' => $item['icon'],
            ]);
    }

    /**
     * Renders search box
     * @return string the rendering result
     */
    protected function renderSearch()
    {
        $defaultFormOptions = ['options' => ['class' => 'sidebar-search']];
        $defaultInputOptions = [
            'name' => 'search',
            'value' => '',
            'options' => [
                'placeholder' => 'Search...',
                'class' => 'form-control',
            ]
        ];
        $formOptions = ArrayHelper::merge(ArrayHelper::getValue($this->search, 'form', []), $defaultFormOptions);
        $inputOptions = ArrayHelper::merge(ArrayHelper::getValue($this->search, 'input', []), $defaultInputOptions);
        ob_start();
        ob_implicit_flush(false);
        echo Html::tag('span', '&nbsp;', ['class' => 'hor-menu-search-form-toggler']);
        echo '<div class="search-form">';
        CoreActiveForm::begin($formOptions);
        echo '<div class="input-group">';
        echo Html::input('text', $inputOptions['name'],  $inputOptions['value'], $inputOptions['options']);
        echo '<div class="input-group-btn">';
        echo '<button type="button" class="btn"></button>';
        echo '</div>'; // end .input-group-btn
        echo '</div>'; // end .input-group
        echo '</div>'; // end .search-form
        CoreActiveForm::end();

        return ob_get_clean();
    }
}