<?php
/**
 * @link http://yii2metronic.icron.org/
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ActiveField extends \yii\widgets\ActiveField
{
    /**
     * @var ActiveForm the form that this field is associated with.
     */
    public $form;
    // icon position
    const ICON_POSITION_LEFT = 'left';
    const ICON_POSITION_RIGHT = 'right';

    /**
     * Renders the closing tag of the field container.
     * @return string the rendering result.
     */
    public function end()
    {
        return Html::endTag(isset($this->options['tag']) ? $this->options['tag'] : 'div') . "\n";
    }
    /**
     * Generates a icon for input.
     * @param array $options icon options.
     * The options have following structure:
     * ```php
     * [
     *     'icon' => 'fa fa-bookmark-o',
     *     'position' => ActiveField::ICON_POSITION_LEFT,
     * ]
     * ```
     * @return static the field object itself
     */
    public function icon($options = [])
    {
        $icon = ArrayHelper::remove($options, 'icon', null);
        if($icon) {
            $position = ArrayHelper::remove($options, 'position', self::ICON_POSITION_LEFT);
            if ($position != self::ICON_POSITION_RIGHT) {
                $position = '';
            }
            $this->parts['{input}'] = Html::tag('i', '', ['class' => $icon]) . "\n" . $this->parts['{input}'];
            $this->parts['{input}'] = Html::tag('div', $this->parts['{input}'], ['class' => 'input-icon ' . $position]);
        }

        return $this;
    }
    /**
     * Generates a groupAddon for input.
     * GroupAddon similar to [[icon()]].
     * @param array $options icon options.
     * The options have following structure:
     * ```php
     * [
     *     'icon' => 'fa fa-bookmark-o',
     *     'position' => ActiveField::ICON_POSITION_LEFT,
     * ]
     * ```
     * @return static the field object itself
     */
    public function groupAddon($options = [])
    {
        $icon = ArrayHelper::remove($options, 'icon', null);
        if($icon) {
            $addon = Html::tag('span', Html::tag('i', '', ['class' => $icon]), ['class' => 'input-group-addon']);
            $position = ArrayHelper::remove($options, 'position', self::ICON_POSITION_LEFT);
            if ($position == self::ICON_POSITION_RIGHT) {
                $this->parts['{input}'] .= "\n" . $addon;
            } else {
                $this->parts['{input}'] = $addon . "\n" . $this->parts['{input}'];
            }
            $this->parts['{input}'] = Html::tag('div', $this->parts['{input}'], ['class' => 'input-group']);
        }

        return $this;
    }

    /**
     * Generates a tag that contains error.
     * @param $error string the error to use.
     * @param array $options the tag options in terms of name-value pairs. It will be merged with [[errorOptions]].
     * @return static the field object itself
     */
    public function staticError($error, $options = [])
    {
        $options = array_merge($this->errorOptions, $options);
        $tag = isset($options['tag']) ? $options['tag'] : 'div';
        unset($options['tag']);
        $this->parts['{error}'] = Html::tag($tag, $error, $options);

        return $this;
    }

    /**
     * Generates spinner component.
     * @param array $options spinner options
     * @return $this
     */
    public function spinner($options = [])
    {
        $this->parts['{input}'] = Spinner::widget(array_merge($options, ['model' => $this->model, 'attribute' => $this->attribute]));

        return $this;
    }

    /**
     * Generates dateRangePicker component [[DateRangePicker]].
     * @param array $options dateRangePicker options
     * @return $this
     */
    public function dateRangePicker($options = [])
    {
        if ($this->form->type == ActiveForm::TYPE_VERTICAL) {
           //$options = array_merge($options, ['options' => ['style' => 'display:table-cell;']]);
           $options = array_merge($options, ['options' => ['class' => 'show']]);
        }
        $this->parts['{input}'] = DateRangePicker::widget(array_merge($options, ['model' => $this->model, 'attribute' => $this->attribute]));

        return $this;
    }

    /**
     * Generates dateRangePicker component [[DateRangePicker]].
     * @param array $options dateRangePicker options
     * @return $this
     */
    public function datePicker($options = [])
    {
        /*if ($this->form->type == ActiveForm::TYPE_VERTICAL) {
            //$options = array_merge($options, ['options' => ['style' => 'display:table-cell;']]);
            $options = array_merge($options, ['options' => ['class' => 'show']]);
        }*/
        $this->parts['{input}'] = DatePicker::widget(array_merge($options, ['model' => $this->model, 'attribute' => $this->attribute]));

        return $this;
    }

    /**
     * Generates select2 component [[Select2]].
     * @param array $options select2 options
     * @return $this
     */
    public function select2($options = [])
    {
        $this->parts['{input}'] = Select2::widget(array_merge($options, ['model' => $this->model, 'attribute' => $this->attribute]));

        return $this;
    }

    /**
     * Generates multiSelect component [[MultiSelect]].
     * @param array $options multiSelect options
     * @return $this
     */
    public function multiSelect($options = [])
    {
        $this->parts['{input}'] = MultiSelect::widget(array_merge($options, ['model' => $this->model, 'attribute' => $this->attribute]));

        return $this;
    }

    public function range($options = [])
    {
        $this->parts['{input}'] = IonRangeSlider::widget(array_merge($options, ['model' => $this->model, 'attribute' => $this->attribute]));

        return $this;
    }
}