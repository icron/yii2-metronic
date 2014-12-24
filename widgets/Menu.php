<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use icron\metronic\Metronic;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;
use yii\widgets\ActiveForm as CoreActiveForm;

/**
 * Metronic menu displays a multi-level menu using nested HTML lists.
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
 * The following example shows how to use Menu:
 *
 * ```php
 * echo Menu::widget([
 *     'items' => [
 *         // Important: you need to specify url as 'controller/action',
 *         // not just as 'controller' even if default action is used.
 *         [
 *           'icon' => '',
 *           'label' => 'Home',
 *           'url' => ['site/index']
 *         ],
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
 * ]
 * ]);
 * ```
 *
 */
class Menu extends \yii\widgets\Menu
{
    /**
     * @var boolean whether to activate parent menu items when one of the corresponding child menu items is active.
     * The activated parent menu items will also have its CSS classes appended with [[activeCssClass]].
     */
    public $activateParents = true;
    /**
     * @var string the CSS class that will be assigned to the first item in the main menu or each submenu.
     */
    public $firstItemCssClass = 'start';
    /**
     * @var string the CSS class that will be assigned to the last item in the main menu or each submenu.
     */
    public $lastItemCssClass = 'last';
    /**
     * @var string the template used to render a list of sub-menus.
     * In this template, the token `{items}` will be replaced with the renderer sub-menu items.
     */
    public $submenuTemplate = "\n<ul class='sub-menu'>\n{items}\n</ul>\n";
    /**
     * @var string the template used to render the body of a menu which is a link.
     * In this template, the token `{url}` will be replaced with the corresponding link URL;
     * while `{label}` will be replaced with the link text.
     * The token `{icon}` will be replaced with the corresponding link icon.
     * The token `{arrow}` will be replaced with the corresponding link arrow.
     * This property will be overridden by the `template` option set in individual menu items via [[items]].
     */
    public $linkTemplate = '<a href="{url}">{icon}{label}{arrow}{badge}</a>';
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
     * @var bool Indicates whether menu is visible.
     */
    public $visible = true;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        Metronic::registerThemeAsset($this->getView());
        Html::addCssClass($this->options, 'page-sidebar-menu');
        if (!$this->visible || Metronic::getComponent()->layoutOption == Metronic::LAYOUT_FULL_WIDTH) {
            Html::addCssClass($this->options, 'visible-sm visible-xs');
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        echo Html::beginTag('div', ['class' => 'page-sidebar navbar-collapse collapse']);
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = $_GET;
        }
        $items = $this->normalizeItems($this->items, $hasActiveChild);
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'ul');
        $data = [Html::tag('li', Html::tag('div', '', ['class' => 'sidebar-toggler hidden-phone']))];

        if (!isset($this->search['visible'])) {
            throw new InvalidConfigException("The 'visible' option of search is required.");
        }
        $data[] = Html::tag('li', $this->renderSearch());
        $data[] = $this->renderItems($items);
        echo Html::tag($tag, implode("\n", $data), $options);
        echo Html::endTag('div');
    }

    /**
     * Renders search box
     * @return string the rendering result
     */
    public function renderSearch()
    {
        $defaultFormOptions = ['options' => ['class' => 'sidebar-search']];
        $defaultInputOptions = ['name' => 'search', 'value' => '', 'options' => ['placeholder' => 'Search...']];
        $formOptions = ArrayHelper::merge(ArrayHelper::getValue($this->search, 'form', []), $defaultFormOptions);
        $inputOptions = ArrayHelper::merge(ArrayHelper::getValue($this->search, 'input', []), $defaultInputOptions);
        ob_start();
        ob_implicit_flush(false);
        CoreActiveForm::begin($formOptions);
        echo '<div class="form-container">';
        echo '<div class="input-box">';
        echo '<a href="#" class="remove"></a>';
        echo Html::input('text', $inputOptions['name'],  $inputOptions['value'], $inputOptions['options']);
        echo '<input type="button" class="submit">';
        echo '</div>';
        echo '</div>';
        CoreActiveForm::end();

        return ob_get_clean();
    }

    /**
     * Recursively renders the menu items (without the container tag).
     * @param array $items the menu items to be rendered recursively
     * @param integer $level the item level, starting with 1
     * @return string the rendering result
     */
    protected function renderItems($items, $level = 1)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
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

            // set parent flag
            $item['level'] = $level;
            $menu = $this->renderItem($item);
            if (!empty($item['items'])) {
                $menu .= strtr($this->submenuTemplate, [
                    '{items}' => $this->renderItems($item['items'], $level + 1),
                ]);
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
        $icon = isset($item['icon']) ? Html::tag('i', '', ['class' => $item['icon']]) : '';
        $label = ($item['level'] == 1) ?
            Html::tag('span', $item['label'], ['class' => 'title']) : (' ' . $item['label']);
        $arrow = !empty($item['items']) ?
            Html::tag('span', '', ['class' => 'arrow' . ($item['active'] ? ' open' : '')]) : '';

        if ($item['active'] && $item['level'] == 1) {
            $arrow = Html::tag('div', '', ['class' => 'selected']) . $arrow;
        }
        $badge =  ArrayHelper::getValue($item, 'badge', '');
        if (isset($item['url'])) {
            $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);
            return strtr($template, [
                '{url}' => Url::toRoute($item['url']),
                '{label}' => $label,
                '{icon}' => $icon,
                '{arrow}' => $arrow,
                '{badge}' => $badge,
            ]);
        } else {
            $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);
            return strtr($template, [
                '{label}' => $label,
                '{icon}' => $icon,
                '{arrow}' => $arrow,
                '{badge}' => $badge,
            ]);
        }
    }
}