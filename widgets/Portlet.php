<?php
/**
 * @link http://yii2metronic.icron.org/
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use icron\metronic\Metronic;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Portlet renders a metronic portlet.
 * Any content enclosed between the [[begin()]] and [[end()]] calls of Portlet
 * is treated as the content of the portlet.
 * For example,
 *
 * ```php
 * // Simple portlet
 * Portlet::begin([
 *   'icon' => 'fa fa-bell-o',
 *   'title' => 'Title Portlet',
 * ]);
 * echo 'Body portlet';
 * Portlet::end();
 *
 * // Portlet with tools, actions, scroller, events and remote content
 * Portlet::begin([
 *   'title' => 'Extended Portlet',
 *   'scroller' => [
 *     'height' => 150,
 *     'footer' => ['label' => 'Show all', 'url' => '#'],
 *   ],
 *   'clientOptions' => [
 *     'loadSuccess' => new \yii\web\JsExpression('function(){ console.log("load success"); }'),
 *     'remote' => '/?r=site/about',
 *   ],
 *   'clientEvents' => [
 *     'close.mr.portlet' => 'function(e) { console.log("portlet closed"); e.preventDefault(); }'
 *   ],
 *   'tools' => [
 *     Portlet::TOOL_RELOAD,
 *     Portlet::TOOL_MINIMIZE,
 *     Portlet::TOOL_CLOSE,
 *   ],
 * ]);
 * ```
 *
 * @see http://yii2metronic.icron.org/components.html#portlet
 * @author icron.org <arbuscula@gmail.com>
 * @since 1.0
 */
class Portlet extends Widget
{
    //types of the portlet
    const TYPE_BOX = 'box';
    const TYPE_SOLID = 'solid';
    const TYPE_NONE = '';
    // color scheme
    const COLOR_LIGHT_BLUE = 'light-blue';
    const COLOR_BLUE = 'blue';
    const COLOR_RED = 'red';
    const COLOR_YELLOW = 'yellow';
    const COLOR_GREEN = 'green';
    const COLOR_PURPLE = 'purple';
    const COLOR_LIGHT_GRAY = 'light-grey';
    const COLOR_GRAY = 'grey';
    //tools
    const TOOL_MINIMIZE = 'collapse';
    const TOOL_MODAL = 'modal';
    const TOOL_RELOAD = 'reload';
    const TOOL_CLOSE = 'remove';
    /**
     * @var string The portlet title
     */
    public $title;
    /**
     * @var string The portlet icon
     */
    public $icon;
    /**
     * @var string The portlet type
     * Valid values are 'box', 'solid', ''
     */
    public $type = self::TYPE_BOX;
    /**
     * @var string The portlet color
     * Valid values are 'light-blue', 'blue', 'red', 'yellow', 'green', 'purple', 'light-grey', 'grey'
     */
    public $color = self::COLOR_BLUE;
    /**
     * @var array List of actions, where each element must be specified as a string.
     */
    public $actions = [];
    /**
     * @var array The portlet tools
     * Valid values are 'collapse', 'modal', 'reload', 'remove'
     */
    public $tools = [];
    /**
     * @var array Scroller options
     * is an array of the following structure:
     * ```php
     * [
     *   // required, height of the body portlet as a px
     *   'height' => 150,
     *   // optional, HTML attributes of the scroller
     *   'options' => [],
     *   // optional, footer of the scroller. May contain string or array(the options of Link component)
     *   'footer' => [
     *     'label' => 'Show all',
     *   ],
     * ]
     * ```
     */
    public $scroller = [];
    /**
     * @var bool Whether the portlet should be bordered
     */
    public $bordered = false;
    /**
     * @var array The HTML attributes for the widget container
     */
    public $options = [];
    /**
     * @var array The HTML attributes for the widget body container
     */
    public $bodyOptions = [];
    /**
     * @var array The HTML attributes for the widget body container
     */
    public $headerOptions = [];

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        Html::addCssClass($this->options, 'portlet ' . $this->color . ' ' . $this->type);
        echo Html::beginTag('div', $this->options);
        Html::addCssClass($this->headerOptions, 'portlet-title');
        echo Html::beginTag('div', $this->headerOptions);
        $icon = ($this->icon) ? Html::tag('i', '', ['class' => 'fa ' . $this->icon])  : '';
        echo Html::tag('div', $icon . ' ' . $this->title, ['class' => 'caption']);

        if (!empty($this->tools)) {
            $tools = [];
            foreach ($this->tools as $tool) {
                $class = '';
                switch ($tool) {
                    case self::TOOL_CLOSE :
                        $class = 'remove';
                        break;

                    case self::TOOL_MINIMIZE :
                        $class = 'collapse';
                        break;

                    case self::TOOL_RELOAD :
                        $class = 'reload';
                        break;
                }
                $tools[] = Html::tag('a', '', ['class' => $class, 'href' => '']);
            }
            echo Html::tag('div', implode("\n", $tools), ['class' => 'tools']);
        }

        if (!empty($this->actions)) {
            echo Html::tag('div', implode("\n", $this->actions), ['class' => 'actions']);
        }
        echo Html::endTag('div');
        Html::addCssClass($this->bodyOptions, 'portlet-body');
        echo Html::beginTag('div', $this->bodyOptions);
        if (!empty($this->scroller)) {
            if (!isset($this->scroller['height'])) {
                throw new InvalidConfigException("The 'height' option of the scroller is required.");
            }
            $options = ArrayHelper::getValue($this->scroller, 'options', []);
            echo Html::beginTag(
                'div',
                ArrayHelper::merge(
                    ['class' => 'scroller', 'data-always-visible' => '1', 'data-rail-visible' => '0'],
                    $options,
                    ['style' => 'height:' . $this->scroller['height'] . 'px;']
                )
            );
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        if (!empty($this->scroller)) {
            echo Html::endTag('div');
            $footer = ArrayHelper::getValue($this->scroller, 'footer', '');
            if (!empty($footer)) {
                echo Html::beginTag('div', ['class' => 'scroller-footer']);
                if (is_array($footer)) {
                    echo Html::tag('div', Link::widget($footer), ['class' => 'pull-right']);
                } elseif (is_string($footer)) {
                    echo $footer;
                }
                echo Html::endTag('div');
            }
        }
        echo Html::endTag('div'); // End portlet body
        echo Html::endTag('div'); // End portlet div
        $loader = Html::img(Metronic::getAssetsUrl($this->view) . '/img/loading-spinner-grey.gif');
        $this->clientOptions['loader'] = ArrayHelper::getValue($this->clientOptions, 'loader', $loader);
        $this->registerPlugin('portlet');
    }
}
