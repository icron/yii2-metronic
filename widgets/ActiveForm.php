<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */


namespace icron\metronic\widgets;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveFormAsset;

class ActiveForm extends \yii\widgets\ActiveForm
{
    // Buttons align
    const BUTTONS_ALIGN_LEFT = 'left';
    const BUTTONS_ALIGN_RIGHT = 'right';
    // Buttons position
    const BUTTONS_POSITION_TOP = 'top';
    const BUTTONS_POSITION_BOTTOM = 'bottom';
    // Form type
    const TYPE_HORIZONTAL = 'horizontal';
    const TYPE_VERTICAL = 'vertical';
    const TYPE_INLINE = 'inline';
    /**
     * @var bool Indicates whether form rows is separated.
     */
    public $separated = false;
    /**
     * @var bool Indicates whether form rows is stripped.
     */
    public $stripped = false;
    /**
     * @var bool Indicates whether form rows is bordered.
     */
    public $bordered = false;
    /**
     * @var string ActiveForm type.
     * Valid values are 'horizontal', 'vertical', 'inline'
     */
    public $type = self::TYPE_VERTICAL;
    /**
     * @var array the [[ActiveForm]] buttons.
     * Note that if are empty option 'items', then will not generated element is wrapped buttons.
     * It is an array of the following structure:
     * ```php
     * [
     *     //optional, horizontal align
     *     'align' => ActiveForm::BUTTONS_POSITION_LEFT,
     *     //optional, vertical position
     *     'position' => ActiveForm::BUTTONS_POSITION_BOTTOM,
     *      //optional, array of buttons
     *     'items' => [
     *         Button::widget('label' => 'Save', 'options' => ['type' => 'submit']),
     *         Button::widget('label' => 'Back'),
     *     ],
     *     // optional, the HTML attributes (name-value pairs) for the form actions tag.
     *     'options' => ['class' => 'fluid']
     * ]
     * ```
     */
    public $buttons = [];
    /**
     * @var array the default configuration used by [[field()]] when creating a new field object.
     */
    public $fieldConfig = [];

    /**
     * @var bool indicates whether the tag 'form' is rendered.
     * In case 'true' widget renders 'div' instead 'form'.
     */
    public $fake = false;
    /**
     * Initializes the widget.
     * This renders the form open tag.
     */
    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        switch ($this->type) {
            case self::TYPE_HORIZONTAL:
                if ($this->stripped) {
                    Html::addCssClass($this->options, 'form-row-stripped');
                }
                if ($this->separated) {
                    Html::addCssClass($this->options, 'form-row-seperated');
                }
                if ($this->bordered) {
                    Html::addCssClass($this->options, 'form-bordered');
                }
                Html::addCssClass($this->options, 'form-horizontal');
                $this->fieldConfig = ArrayHelper::merge([
                        'labelOptions' => ['class' => 'col-md-3 control-label'],
                        'template' => "{label}\n" . Html::tag('div', "{input}\n{error}\n{hint}", ['class' => 'col-md-9']),
                    ], $this->fieldConfig);
                break;
            case self::TYPE_INLINE:
                Html::addCssClass($this->options, 'form-inline');
                $this->fieldConfig = ArrayHelper::merge([
                        'labelOptions' => ['class' => 'sr-only'],
                    ], $this->fieldConfig);
                break;
        }
        if (!isset($this->fieldConfig['class'])) {
            $this->fieldConfig['class'] = ActiveField::className();
        }
        if ($this->fake) {
            echo Html::beginTag('div', $this->options);
        } else {
            echo Html::beginForm($this->action, $this->method, $this->options);
        }
        echo $this->renderActions(self::BUTTONS_POSITION_TOP);
        echo Html::beginTag('div', ['class' => 'form-body']);
    }
    /**
     * Runs the widget.
     * This registers the necessary javascript code and renders the form close tag.
     */
    public function run()
    {
        echo Html::endTag('div');
        echo $this->renderActions(self::BUTTONS_POSITION_BOTTOM);
        if (!empty($this->attributes)) {
            $id = $this->options['id'];
            $options = Json::encode($this->getClientOptions());
            $attributes = Json::encode($this->attributes);
            $view = $this->getView();
            ActiveFormAsset::register($view);
            $view->registerJs("jQuery('#$id').yiiActiveForm($attributes, $options);");
        }
        if ($this->fake) {
            echo Html::endTag('div');
        } else {
            echo Html::endForm();
        }
    }

    /**
     * Generates a form field.
     * A form field is associated with a model and an attribute. It contains a label, an input and an error message
     * and use them to interact with end users to collect their inputs for the attribute.
     * @param Model $model the data model
     * @param string $attribute the attribute name or expression. See [[Html::getAttributeName()]] for the format
     * about attribute expression.
     * @param array $options the additional configurations for the field object
     * @return ActiveField the created ActiveField object
     * @see fieldConfig
     */
    public function field($model, $attribute, $options = []) {
        return parent::field($model, $attribute, $options);
    }

    protected function renderActions($currentPosition)
    {
        $position = ArrayHelper::getValue($this->buttons, 'position', self::BUTTONS_POSITION_BOTTOM);
        if (!empty($this->buttons['items']) && $position == $currentPosition) {
            $actionsOptions = ArrayHelper::getValue($this->buttons, 'options', []);
            Html::addCssClass($actionsOptions, 'form-actions');
            if ($position == self::BUTTONS_POSITION_TOP) {
                Html::addCssClass($actionsOptions, 'top');
            }
            if (isset($this->buttons['align']) && $this->buttons['align'] == self::BUTTONS_ALIGN_RIGHT) {
                Html::addCssClass($actionsOptions, 'right');
            }
            $rowOptions = [];
            $buttons = implode("\n", $this->buttons['items']);
            switch ($this->type) {
                case self::TYPE_HORIZONTAL:
                    Html::addCssClass($actionsOptions, 'fluid');
                    preg_match('#col-md-(\d+)#', $this->fieldConfig['labelOptions']['class'], $matches);
                    if (isset($matches[1])) {
                        $offset = $matches[1];
                        Html::addCssClass($rowOptions, 'col-md-offset-' . $offset);
                        Html::addCssClass($rowOptions, 'col-md-' . 12 - $offset);
                        $buttons = Html::tag('div', $buttons, $rowOptions);
                    }
                    break;
            }

            return Html::tag('div', $buttons, $actionsOptions);
        }

        return '';
    }
}