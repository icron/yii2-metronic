<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace  icron\metronic\widgets;

use yii\web\AssetBundle;

/**
 * IonRangeSliderAsset for slider widget.
 */
class IonRangeSliderAsset extends AssetBundle
{
    public $sourcePath = '@icron/metronic/assets';
    public $js = [
        'plugins/ion.rangeslider/js/ion-rangeSlider/ion.rangeSlider.min.js',
    ];

    public  $css = [
        'plugins/ion.rangeslider/css/ion.rangeSlider.css',
        'plugins/ion.rangeslider/css/ion.rangeSlider.Metronic.css',
    ];

    public $depends = [
        'icron\metronic\CoreAsset',
    ];
}
