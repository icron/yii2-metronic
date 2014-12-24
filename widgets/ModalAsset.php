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
class ModalAsset extends AssetBundle
{
    public $sourcePath = '@icron/metronic/assets';
    public $js = [
        'plugins/bootstrap-modal/js/bootstrap-modalmanager.js',
        'plugins/bootstrap-modal/js/bootstrap-modal.js',
    ];

    public  $css = [
        'plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css',
        'plugins/bootstrap-modal/css/bootstrap-modal.css',
    ];

    public $depends = [
        'icron\metronic\CoreAsset',
    ];
}
