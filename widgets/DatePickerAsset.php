<?php
/**
 * @link http://yii2metronic.icron.org/
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;
use yii\web\AssetBundle;


class DatePickerAsset extends AssetBundle
{
    public $sourcePath = '@icron/metronic/assets';

    public static $extraJs = [];

	public $js = [
        'plugins/bootstrap-daterangepicker/moment.min.js',
		'plugins/bootstrap-datepicker-extended/js/bootstrap-datepicker.js',
	];
    public $css = [
        'plugins/bootstrap-datepicker-extended/css/datepicker.css',
        'plugins/bootstrap-datepicker-extended/css/datepicker3.css',
    ];
	public $depends = [
        'icron\metronic\CoreAsset',
	];

    public function init()
    {
        $this->js = array_merge($this->js, static::$extraJs);
    }
}
