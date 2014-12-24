<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Alert renders an alert bootstrap component.
 *
 * For example,
 *
 * ```php
 * echo Alert::widget([
 *     'body' => 'Say hello...',
 *     'closeButton' => [
 *         'label' => '&times;',
 *         'tag' => 'a',
 *         'type' => Alert::TYPE_DANGER,
 *     ],
 * ]);
 * ```
 *
 * The following example will show the content enclosed between the [[begin()]]
 * and [[end()]] calls within the alert box:
 *
 * ```php
 * Alert::begin([
 *     'type' => Alert::TYPE_DANGER,
 *     'closeButton' => ['label' => '&times;'],
 * ]);
 *
 * echo 'Say hello...';
 *
 * Alert::end();
 * ```
 */
class Alert extends \yii\bootstrap\Alert
{
    // type
    const TYPE_SUCCESS = 'success';
    const TYPE_INFO = 'info';
    const TYPE_WARNING = 'warning';
    const TYPE_DANGER = 'danger';
    /**
     * @var string the note type.
     * Valid values  are 'success', 'info', 'warning', 'danger'.
     */
	public $type = self::TYPE_SUCCESS;
    /**
     * @var boolean when set, alert has a larger block size.
     */
    public $block = true;
    /**
     * @var boolean when set, alert will fade out using transitions when closed.
     */
    public $fade = true;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        Html::addCssClass($this->options, 'alert-'.$this->type);
        if ($this->block) {
            Html::addCssClass($this->options, 'alert-block');
        }
        if ($this->fade) {
            Html::addCssClass($this->options, 'fade in');
        }
        parent::init();
    }
}
