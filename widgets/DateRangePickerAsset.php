<?php
/**
 * @link http://yii2metronic.icron.org/
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace  icron\metronic\widgets;

use yii\web\AssetBundle;

/**
 * DateRangePickerAsset for dateRangePicker widget.
 */
class DateRangePickerAsset extends AssetBundle
{
    public $sourcePath = '@icron/metronic/assets';
    public $js = [
        'plugins/bootstrap-daterangepicker/moment.min.js',
        'plugins/bootstrap-daterangepicker/daterangepicker.js',
    ];

    public  $css = [
        'plugins/bootstrap-daterangepicker/daterangepicker-bs3.css',
        'plugins/bootstrap-datetimepicker/css/datetimepicker.css',
    ];

    public $depends = [
        'icron\metronic\CoreAsset',
    ];
}
