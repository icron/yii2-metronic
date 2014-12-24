<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use icron\metronic\Metronic;
use yii\helpers\Html;
use Yii;

/**
 * NavBar renders a navbar HTML component.
 *
 * Any content enclosed between the [[begin()]] and [[end()]] calls of NavBar
 * is treated as the content of the navbar. You may use widgets such as [[Nav]]
 * or [[\yii\widgets\Menu]] to build up such content. For example,
 *
 * ```php
 * use yii\bootstrap\NavBar;
 * use yii\widgets\Menu;
 *
 * NavBar::begin([
 *     'brandLabel' => 'NavBar Test',
 *     'brandLogoUrl' => '/img/logo.png',
 * ]);
 * echo Nav::widget([
 *     'items' => [
 *         ['label' => 'Home', 'url' => ['/site/index']],
 *         ['label' => 'About', 'url' => ['/site/about']],
 *     ],
 * ]);
 * NavBar::end();
 * ```
 *
 * @see http://twitter.github.io/bootstrap/components.html#navbar
 */
class NavBar extends \yii\bootstrap\NavBar
{
    /**
     * @var string the url to logo of the brand.
     */
    public $brandLogoUrl;

    /**
     * Renders toggle button
     * @return string the rendering result
     */
    protected function renderToggleButton()
    {
        return Html::tag(
            'a',
            Html::img(Metronic::getAssetsUrl($this->view) . '/img/menu-toggler.png'),
            [
                'href' => '#',
                'class' => 'navbar-toggle',
                'data-toggle' => 'collapse',
                'data-target' => '.navbar-collapse'
            ]
        );
    }

    /**
     * Renders Brand
     * @return string the rendering result
     */
    protected function renderBrand()
    {
        if ($this->brandLogoUrl) {
            $content = Html::img($this->brandLogoUrl, ['class' => 'img-responsive','alt' => $this->brandLabel]);
        } else {
            $content = $this->brandLabel;
        }
        Html::addCssClass($this->brandOptions, 'navbar-brand');
        $this->brandOptions['href'] = $this->brandUrl;
        return Html::tag('a', $content, $this->brandOptions);
    }

    /**
     * Initializes the widget.
     */
    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        Html::addCssClass($this->options, 'mega-menu');
        echo Html::beginTag('div', $this->options);
        echo Html::beginTag('div', ['class' => 'header-inner']);
        echo $this->renderBrand();
        echo $this->renderToggleButton();
    }

    /**
     * Executes the widget.
     */
    public function run()
    {
        echo Html::endTag('div');
        echo Html::endTag('div');
        echo Html::tag('div', '', ['class' => 'clearfix']);
    }
}