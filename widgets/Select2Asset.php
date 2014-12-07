<?php
/**
 * @link http://yii2metronic.icron.org/
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace  icron\metronic\widgets;

use yii\web\AssetBundle;

/**
 * SpinnerAsset for spinner widget.
 */
class Select2Asset extends AssetBundle
{
    public $sourcePath = '@icron/metronic/assets';
    public $js = [
        'plugins/select2/select2.min.js',
    ];

    public $css = [
        'plugins/select2/select2.css',
        'plugins/select2/select2-metronic.css',
    ];


    public $depends = [
        'icron\metronic\CoreAsset',
    ];
}
