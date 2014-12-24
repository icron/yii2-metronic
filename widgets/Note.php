<?php
/**
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;
use yii\helpers\Html;


/**
 * Note renders a metronic button.
 *
 * For example,
 * ```php
 * Note::widget([
 *     'title' => 'Success! Some Header Goes Here',
 *     'body' => 'Duis mollis, est non commodo luctus',
 *     'type' => Note::TYPE_INFO,
 * ]);
 * ```
 *
 * The following example will show the content enclosed between the [[begin()]]
 * and [[end()]] calls within the alert box:
 * ```php
 * Note::begin(['type' => Note::TYPE_DANGER]);
 * echo 'Some title and body';
 * Note::end();
 * ```
 */
class Note extends  Widget
{
    const  TYPE_DANGER = 'danger';
    const  TYPE_INFO = 'info';
    const  TYPE_SUCCESS = 'success';
    const  TYPE_WARNING = 'warning';

    /**
     * @var string the note title
     */
    public $title;
    /**
     * @var string the note body
     */
    public $body;
    /**
     * @var string the note type.
     * Valid values  are 'danger', 'info', 'success', 'warning'.
     */
    public $type = self::TYPE_SUCCESS;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        Html::addCssClass($this->options, 'note note-' . $this->type);
        echo Html::beginTag('div', $this->options);
        echo $this->renderTitle();
    }

    /**
     * Executes the widget.
     */
    public function run()
    {
        echo $this->renderBody();
        echo Html::endTag('div');
    }

    /**
     * Renders title
     * @return string the rendering result
     */
    public function renderTitle()
    {
        return !empty($this->title) ? Html::tag('h4', $this->title, ['class' => 'block']) : '';
    }

    /**
     * Renders body
     * @return string the rendering result
     */
    public function renderBody()
    {
        return !empty($this->body) ? Html::tag('p', $this->body) : '';
    }
}
