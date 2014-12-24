<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use Yii;

/**
 * Breadcrumbs displays a list of links indicating the position of the current page in the whole site hierarchy.
 *
 * For example, breadcrumbs like "Home / Sample Post / Edit" means the user is viewing an edit page
 * for the "Sample Post". He can click on "Sample Post" to view that page, or he can click on "Home"
 * to return to the homepage.
 *
 * To use Breadcrumbs, you need to configure its [[links]] property, which specifies the links to be displayed. For example,
 *
 * ```php
 * // $this is the view object currently being used
 * echo Breadcrumbs::widget([
 *     'links' => [
 *         ['label' => 'Sample Post', 'url' => ['post/edit', 'id' => 1]],
 *         'Edit',
 *     ],
 * ]);
 * ```
 *
 * Because breadcrumbs usually appears in nearly every page of a website, you may consider placing it in a layout view.
 * You can use a view parameter (e.g. `$this->params['breadcrumbs']`) to configure the links in different
 * views. In the layout view, you assign this view parameter to the [[links]] property like the following:
 *
 * ```php
 * // $this is the view object currently being used
 * echo Breadcrumbs::widget([
 *     'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
 * ]);
 * ```
 */
class Breadcrumbs extends \yii\widgets\Breadcrumbs
{
    /**
     * @var string the template used to render each inactive item in the breadcrumbs. The token `{link}`
     * will be replaced with the actual HTML link for each inactive item.
     */
    public $itemTemplate = "<li>{link}<i class=\"fa fa-angle-right\"></i></li>\n";
    /**
     * @var string the template used to render each active item in the breadcrumbs. The token `{link}`
     * will be replaced with the actual HTML link for each active item.
     */
    public $activeItemTemplate = "<li>{link}<i class=\"fa fa-angle-right\"></i></li>\n";
    /**
     * @var array|string
     */
    public $actions;

    public function init()
    {
        parent::init();
        Html::addCssClass($this->options, 'page-breadcrumb breadcrumb');
    }


    public function run()
    {
        if (empty($this->links)) {
            return;
        }
        $links = [];

        if ($this->actions !== null) {
            Html::addCssClass($this->actions['dropdown']['options'], 'pull-right');
            if (is_string($this->actions)) {
                $links[] = $this->actions;
            } else if (is_array($this->actions)) {
                $links[] = ButtonDropdown::widget($this->actions);
            } else {
                throw new InvalidConfigException('Actions must be of type "string" or "array".');
            }
        }

        if ($this->homeLink === null) {
            $links[] = $this->renderItem([
                    'label' => Yii::t('yii', 'Home'),
                    'url' => Yii::$app->homeUrl,
                ], $this->itemTemplate);
        } elseif ($this->homeLink !== false) {
            $links[] = $this->renderItem($this->homeLink, $this->itemTemplate);
        }
        foreach ($this->links as $link) {
            if (!is_array($link)) {
                $link = ['label' => $link];
            }
            $links[] = $this->renderItem($link, isset($link['url']) ? $this->itemTemplate : $this->activeItemTemplate);
        }
        echo Html::tag($this->tag, implode('', $links), $this->options);
    }

    /**
     * Renders a single breadcrumb item.
     * @param array $link the link to be rendered. It must contain the "label" element. The "url" element is optional.
     * @param string $template the template to be used to rendered the link. The token "{link}" will be replaced by the link.
     * @return string the rendering result
     * @throws InvalidConfigException if `$link` does not have "label" element.
     */
    protected function renderItem($link, $template)
    {
        if (isset($link['label'])) {
            $label = $this->encodeLabels ? Html::encode($link['label']) : $link['label'];
        } else {
            throw new InvalidConfigException('The "label" element is required for each link.');
        }

        $icon = ArrayHelper::getValue($link, 'icon', '');
        if ($icon) {
            $icon = Html::tag('i', '', ['class' => 'fa ' . $icon]) . ' ';
        }
        if (isset($link['url'])) {
            return strtr($template, ['{link}' => $icon . Html::a($label, $link['url'])]);
        } else {
            return strtr($template, ['{link}' => $icon . $label]);
        }
    }
}