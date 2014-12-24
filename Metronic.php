<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic;

use Yii;
use yii\web\AssetBundle;
use yii\web\View;

/**
 * This is the class Metronic Component
 */
class Metronic extends \yii\base\Component
{
    /**
     * @var AssetBundle
     */
    public static $assetsBundle;

    // layout
    const LAYOUT_FLUID = '';
    const LAYOUT_BOXED = 'page-boxed';
    const LAYOUT_FULL_WIDTH = 'page-full-width';

    //color
    const COLOR_BLACK = 'default';
    const COLOR_BLUE = 'blue';
    const COLOR_BROWN = 'brown';
    const COLOR_PURPLE = 'purple';
    const COLOR_GRAY = 'gray';
    const COLOR_LIGHT = 'light';

    //header
    const HEADER_FIXED = 'fixed';
    const HEADER_DEFAULT = 'default';

    //sidebar
    const SIDEBAR_FIXED = 'fixed';
    const SIDEBAR_DEFAULT = 'default';

    //sidebar position
    const SIDEBAR_POSITION_LEFT = 'left';
    const SIDEBAR_POSITION_RIGHT = 'right';

    //footer
    const FOOTER_DEFAULT = 'default';
    const FOOTER_FIXED = 'fixed';

    /**
     * @var string Theme color
     */
    public $color = self::COLOR_BLACK;
    /**
     * @var string Layout mode
     */
    public $layoutOption = self::LAYOUT_FLUID;
    /**
     * @var string Header display
     */
    public $headerOption = self::HEADER_DEFAULT;
    /**
     * @var string Sidebar display
     */
    public $sidebarOption = self::SIDEBAR_DEFAULT;
    /**
     * @var string Sidebar position
     */
    public $sidebarPosition = self::SIDEBAR_POSITION_LEFT;
    /**
     * @var string Footer display
     */
    public $footerOption = self::FOOTER_DEFAULT;
    /**
     * @var string Component name used in the application
     */
    public static $componentName = 'metronic';

    public function init()
    {
        Yii::$classMap['yii\helpers\Html'] = __DIR__ . '\helpers\Html.php';
    }
    /**
     * @return Metronic Get Metronic component
     */
    public static function getComponent() {
       return Yii::$app->{static::$componentName};
    }

    /**
     * Get base url to metronic assets
     * @param $view View
     * @return string
     */
    public static function getAssetsUrl($view)
    {
        if(static::$assetsBundle === null){
            static::$assetsBundle = static::registerThemeAsset($view);
        }

        return (static::$assetsBundle instanceof AssetBundle) ? static::$assetsBundle->baseUrl : '';
    }

    /**
     * Register Theme Asset
     * @param $view View
     * @return AssetBundle
     */
    public static function registerThemeAsset($view)
    {
        /** @var AssetBundle $themeAsset */
        $themeAsset = 'icron\metronic\Color' . ucfirst(static::getComponent()->color) . 'Asset';
        static::$assetsBundle = $themeAsset::register($view);

        return static::$assetsBundle;
    }
}