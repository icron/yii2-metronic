<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic;

use yii\web\AssetBundle;

class FontAsset extends AssetBundle
{
    public $sourcePath = '@icron/metronic/assets';

    public $css = [
        'css/fonts.css',
        'plugins/font-awesome/css/font-awesome.min.css',
    ];
}
