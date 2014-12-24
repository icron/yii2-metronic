<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic;

use yii\web\AssetBundle;

class CoreAsset extends AssetBundle
{
    public $sourcePath = '@icron/metronic/assets';
    public $jsOptions = [
        'conditions' => [
            'plugins/respond.min.js' => 'if lt IE 9',
            'plugins/excanvas.min.js' => 'if lt IE 9',
        ],
    ];
    public $js = [
        'plugins/respond.min.js',
        'plugins/excanvas.min.js',
        'plugins/jquery-migrate-1.2.1.min.js',
        'plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
        'plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'plugins/jquery.blockui.min.js',
        'plugins/jquery.cokie.min.js',
        'plugins/uniform/jquery.uniform.min.js',
        'scripts/core/metronic.js',
        'scripts/core/app.js',
        'scripts/custom/custom.js',
    ];

    public $css = [
        'plugins/uniform/css/uniform.default.css',
        'css/style-metronic.css',
        'css/style.css',
        'css/style-responsive.css',
        'css/custom.css',
    ];
    public $depends = [
        'icron\metronic\FontAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\web\JqueryAsset',
    ];
}
