<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace  icron\metronic\widgets;

use yii\web\AssetBundle;

/**
 * SpinnerAsset for spinner widget.
 */
class NotificationAsset extends AssetBundle
{
    public $sourcePath = '@icron/metronic/assets';
    public $js = [
        'plugins/bootstrap-toastr/toastr.min.js',
    ];

    public $css = [
        'plugins/bootstrap-toastr/toastr.min.css',
    ];

    public $depends = [
        'icron\metronic\CoreAsset',
    ];
}
