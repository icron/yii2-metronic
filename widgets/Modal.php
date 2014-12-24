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
 * Modal renders a modal window that can be toggled by clicking on a button.
 *
 * The following example will show the content enclosed between the [[begin()]]
 * and [[end()]] calls within the modal window:
 *
 * ~~~php
 * Modal::begin([
 *     'title' => 'Configuration',
 *     'toggleButton' => [
 *          'type' => Button::TYPE_M_GREEN,
 *          'label' => 'Modal',
 *          'icon' => 'fa fa-bell-o',
 *          'fullWidth' => true,
 *          'stackable' => true,
 *      ],
 * ]);
 *
 * echo 'Say hello...';
 *
 * Modal::end();
 * ~~~
 *
 * @see http://twitter.github.io/bootstrap/javascript.html#modals
 */
class Modal extends Widget
{
    /**
     * @var string the title in the modal window.
     */
    public $title;
	/**
	 * @var string the footer content in the modal window.
	 */
	public $footer;
	/**
	 * @var array the options for rendering the close button tag.
	 * The close button is displayed in the header of the modal window. Clicking
	 * on the button will hide the modal window. If this is null, no close button will be rendered.
	 *
	 * The following special options are supported:
	 *
	 * - tag: string, the tag name of the button. Defaults to 'button'.
	 * - label: string, the label of the button. Defaults to '&times;'.
	 *
	 * The rest of the options will be rendered as the HTML attributes of the button tag.
	 * Please refer to the [Modal plugin help](http://twitter.github.com/bootstrap/javascript.html#modals)
	 * for the supported HTML attributes.
	 */
	public $closeButton = [];
    /**
     * @var array the configuration array for [[Button]].
     */
    public $toggleButton = [];
    /**
     * @var bool indicates whether the modal in full screen width.
     */
    public $fullWidth = false;
    /**
     * @var bool indicates whether the modal is stacked.
     */
    public $stackable = false;

    /**
	 * Initializes the widget.
	 */
	public function init()
	{
		parent::init();

		$this->initOptions();

		echo $this->renderToggleButton() . "\n";
		echo Html::beginTag('div', $this->options) . "\n";
		echo $this->renderHeader() . "\n";
		echo $this->renderBodyBegin() . "\n";
	}

	/**
	 * Renders the widget.
	 */
	public function run()
	{
		echo "\n" . $this->renderBodyEnd();
		echo "\n" . $this->renderFooter();
		echo "\n" . Html::endTag('div');

        ModalAsset::register($this->view);
        $this->registerPlugin('spinner');
	}

	/**
	 * Renders the header HTML markup of the modal
	 * @return string the rendering result
	 */
	protected function renderHeader()
	{
		$button = $this->renderCloseButton();
		if ($button !== null) {
			$this->title = $button . "\n" . Html::tag('h4', $this->title, ['class' => 'modal-title']);
		}
		if ($this->title !== null) {
			return Html::tag('div', "\n" . $this->title . "\n", ['class' => 'modal-header']);
		} else {
			return null;
		}
	}

	/**
	 * Renders the opening tag of the modal body.
	 * @return string the rendering result
	 */
	protected function renderBodyBegin()
	{
		return Html::beginTag('div', ['class' => 'modal-body']);
	}

	/**
	 * Renders the closing tag of the modal body.
	 * @return string the rendering result
	 */
	protected function renderBodyEnd()
	{
		return Html::endTag('div');
	}

	/**
	 * Renders the HTML markup for the footer of the modal
	 * @return string the rendering result
	 */
	protected function renderFooter()
	{
		if ($this->footer !== null) {
			return Html::tag('div', "\n" . $this->footer . "\n", ['class' => 'modal-footer']);
		} else {
			return null;
		}
	}

	/**
	 * Renders the toggle button.
	 * @return string the rendering result
	 */
	protected function renderToggleButton()
	{
		if (!empty($this->toggleButton)) {
			return Button::widget($this->toggleButton);
		} else {
			return null;
		}
	}

	/**
	 * Renders the close button.
	 * @return string the rendering result
	 */
	protected function renderCloseButton()
	{
		if ($this->closeButton !== null) {
			$tag = ArrayHelper::remove($this->closeButton, 'tag', 'button');
			$label = ArrayHelper::remove($this->closeButton, 'label', '&times;');
			if ($tag === 'button' && !isset($this->closeButton['type'])) {
				$this->closeButton['type'] = 'button';
			}
			return Html::tag($tag, $label, $this->closeButton);
		} else {
			return null;
		}
	}

	/**
	 * Initializes the widget options.
	 * This method sets the default values for various options.
	 */
	protected function initOptions()
	{
		$this->options = array_merge([
			'class' => 'fade',
			'tabindex' => -1,
		], $this->options);
		Html::addCssClass($this->options, 'modal');
		if ($this->fullWidth) {
		    Html::addCssClass($this->options, 'container');
        }
        if ($this->stackable) {
            $this->options = array_merge($this->options, ['data-focus-on' => 'input:first']);
        }
		if ($this->clientOptions !== false) {
			$this->clientOptions = array_merge(['show' => false], $this->clientOptions);
		}

		if ($this->closeButton !== null) {
			$this->closeButton = array_merge([
				'data-dismiss' => 'modal',
				'aria-hidden' => 'true',
				'class' => 'close',
			], $this->closeButton);
		}

		if (!empty($this->toggleButton)) {
			$this->toggleButton = array_merge([
				'options' => ['data-toggle' => 'modal'],
			], $this->toggleButton);
			if (!isset($this->toggleButton['options']['data-target']) && !isset($this->toggleButton['options']['href'])) {
				$this->toggleButton['options']['data-target'] = '#' . $this->options['id'];
			}
		}
	}
}
