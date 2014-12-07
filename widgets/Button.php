<?php
/**
 * @link http://yii2metronic.icron.org/
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;

/**
 * Button renders a metronic button.
 *
 * For example,
 *
 * ```php
 * echo Button::widget([
 *     'label' => 'Action',
 *     'icon' => 'fa fa-bookmark-o',
 *     'iconPosition' => Button::ICON_POSITION_LEFT,
 *     'size' => Button::SIZE_SMALL,
 *     'disabled' => false,
 *     'block' => false,
 *     'type' => Button::TYPE_M_BLUE,
 * ]);
 * ```
 */
class Button extends \yii\bootstrap\Button
{
    // Button bootstrap types.
    const TYPE_PRIMARY = 'primary';
    const TYPE_INFO = 'info';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_DANGER = 'danger';
    const TYPE_INVERSE = 'inverse';
    const TYPE_LINK = 'link';

    // Button metronic types.
    const TYPE_M_DEFAULT = 'default';
    const TYPE_M_RED = 'red';
    const TYPE_M_BLUE = 'blue';
    const TYPE_M_GREEN = 'green';
    const TYPE_M_YELLOW = 'yellow';
    const TYPE_M_PURPLE = 'purple';
    const TYPE_M_DARK = 'dark';

    // Button sizes.
    const SIZE_MINI = 'xs';
    const SIZE_SMALL = 'sm';
    const SIZE_LARGE = 'lg';

    // Позиция иконки
    const ICON_POSITION_LEFT = 'left';
    const ICON_POSITION_RIGHT = 'right';

    /**
     * @var string The button size.
     * Valid values are 'xs', 'sm', 'lg'.
     */
    public $size;
    /**
     * @var string The button type.
     * Valid values for metronic styles are 'default', 'red', 'blue', 'green', 'yellow', 'purple', 'dark'.
     * Valid values for bootstrap styles are 'primary', 'info', 'success', 'warning', 'danger', 'inverse', 'link'.
     */
    public $type = self::TYPE_M_DEFAULT;
    /**
     * @var string The button icon.
     */
    public $icon;
    /**
     * @var string Icon position.
     * Valid values are 'left', 'right'.
     */
    public $iconPosition = self::ICON_POSITION_LEFT;
    /**
     * @var bool Indicates whether button is disabled or not.
     */
    public $disabled = false;
    /**
     * @var bool Indicates whether the button should span the full width of the a parent.
     */
    public $block = false;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        $bootstrapTypes = [
            self::TYPE_PRIMARY,
            self::TYPE_INFO,
            self::TYPE_SUCCESS,
            self::TYPE_WARNING,
            self::TYPE_DANGER,
            self::TYPE_INVERSE,
            self::TYPE_LINK,
        ];

        $metronicTypes = [
            self::TYPE_M_DEFAULT,
            self::TYPE_M_RED,
            self::TYPE_M_BLUE,
            self::TYPE_M_GREEN,
            self::TYPE_M_YELLOW,
            self::TYPE_M_PURPLE,
            self::TYPE_M_DARK,
        ];

        if (in_array($this->type, $bootstrapTypes)) {
            Html::addCssClass($this->options, 'btn-' . $this->type);
        } elseif (in_array($this->type, $metronicTypes)) {
            Html::addCssClass($this->options, $this->type);
        } else {
            throw new InvalidConfigException("The button type is invalid.");
        }

        $sizes = [
            self::SIZE_MINI,
            self::SIZE_SMALL,
            self::SIZE_LARGE,
        ];

        if (in_array($this->size, $sizes)) {
            Html::addCssClass($this->options, 'btn-' . $this->size);
        }

        if ($this->disabled === true) {
            Html::addCssClass($this->options, 'disabled');
        }

        if ($this->block === true) {
            Html::addCssClass($this->options, 'btn-block');
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $label = $this->encodeLabel ? Html::encode($this->label) : $this->label;
        if ($this->icon !== null) {
            $icon = Html::tag('i', '', ['class' => $this->icon]);
            $label = strcasecmp($this->iconPosition, self::ICON_POSITION_LEFT) === 0 ? ($icon . ' ' . $label) : $label . ' ' . $icon;
        }
        echo Html::tag($this->tagName, $label, $this->options);
        $this->registerPlugin('button');
    }
}