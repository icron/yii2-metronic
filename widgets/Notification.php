<?php
/**
 * @link http://yii2metronic.icron.org/
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use yii\web\JsExpression;
use yii\web\View;


/**
 * Notification renders a notification box that can be opened by clicking on a button.
 *
 * For example,
 * ```php
 * Notification::widget([
 *     'title' => 'Success! Some Header Goes Here',
 *     'body' => 'Duis mollis, est non commodo luctus',
 *     'type' => Notification::TYPE_INFO,
 *     'openButton' => [
 *          'type' => Button::TYPE_M_GREEN,
 *          'label' => 'Notification',
 *          'icon' => 'fa fa-bell-o',
 *      ]
 * ]);
 * ```
 *
 * The following example will show the content enclosed between the [[begin()]]
 * and [[end()]] calls within the alert box:
 * ```php
 * Notification::begin(['type' => Notification::TYPE_DANGER]);
 * echo 'Some title and body';
 * Notification::end();
 * ```
 * @see https://github.com/CodeSeven/toastr
 */
class Notification extends  Widget
{
    // type
    const  TYPE_ERROR = 'error';
    const  TYPE_INFO = 'info';
    const  TYPE_SUCCESS = 'success';
    const  TYPE_WARNING = 'warning';
    // position
    const POSITION_TOP_RIGHT = 'toast-top-right';
    const POSITION_BOTTOM_RIGHT = 'toast-bottom-right';
    const POSITION_BOTTOM_LEFT = 'toast-bottom-left';
    const POSITION_TOP_LEFT = 'toast-top-left';
    const POSITION_TOP_CENTER = 'toast-top-center';
    const POSITION_BOTTOM_CENTER = 'toast-bottom-center';
    const POSITION_FULL_WIDTH = 'toast-top-full-width';
    // easing
    const EASING_LINEAR = 'linear';
    const EASING_SWING = 'swing';

    /**
     * @var string the notification title
     */
    public $title = '';
    /**
     * @var string the notification body
     */
    public $body = '';
    /**
     * @var string the notification type.
     * Valid values  are 'danger', 'info', 'success', 'warning'.
     */
    public $type = self::TYPE_SUCCESS;
    /**
     * @var array the configuration array for [[Button]].
     */
    public $openButton = [];

    /**
     * Executes the widget.
     */
    public function init()
    {
        parent::init();
        $this->initOptions();
        ob_start();
        ob_implicit_flush(false);
    }

    public function run()
    {
        $this->body .= ob_get_clean();
        if (!empty($this->openButton)) {
            /** @var Button $widget */
            $js =  'toastr.options = ' . Json::encode($this->clientOptions) . ';';
            $this->view->registerJs($js, View::POS_READY);
            $this->initOpenButton();
        } else {
            return null;
        }
        NotificationAsset::register($this->view);
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    public function initOptions()
    {
        $defaultOptions = [
            'closeButton'=> true,
            'debug'=> false,
            'positionClass'=> 'toast-top-right',
            'onclick'=> null,
            'showDuration'=> '1000',
            'hideDuration'=> '1000',
            'timeOut'=> '5000',
            'extendedTimeOut'=> '1000',
            'showEasing'=> 'swing',
            'hideEasing'=> 'linear',
            'showMethod'=> 'fadeIn',
            'hideMethod'=> 'fadeOut'
        ];

        $this->clientOptions = array_merge($defaultOptions, $this->clientOptions);
    }

    /**
     * Initializes the widget Button options.
     * This method sets the default values for various options.
     */
    public function initOpenButton()
    {
        $widget = Button::begin($this->openButton);
        $msg = "'{$this->body}', '{$this->title}'";
        $jsOpen = 'toastr.' . $this->type . '(' . $msg . ')';
        if(!isset($widget->clientEvents['click'])) {
            $widget->clientEvents['click'] =  "function(){{$jsOpen};}";
        }
        $widget->run();
    }
}
