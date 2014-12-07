<?php
/**
 * @link http://yii2metronic.icron.org/
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use Yii;

/**
 * DetailView displays the detail of a single data [[model]].
 *
 * DetailView is best used for displaying a model in a regular format (e.g. each model attribute
 * is displayed as a row in a table.) The model can be either an instance of [[Model]]
 * or an associative array.
 *
 * DetailView uses the [[attributes]] property to determines which model attributes
 * should be displayed and how they should be formatted.
 *
 * A typical usage of DetailView is as follows:
 *
 * ~~~
 * echo DetailView::widget([
 *     'model' => $model,
 *     'attributes' => [
 *         'title',             // title attribute (in plain text)
 *         'description:html',  // description attribute in HTML
 *         [                    // the owner name of the model
 *             'label' => 'Owner',
 *             'value' => $model->owner->name,
 *         ],
 *     ],
 * ]);
 * ~~~
 */
class DetailView extends \yii\widgets\DetailView
{
    /**
     * @var string|callback the template used to render a single attribute. If a string, the token `{label}`
     * and `{value}` will be replaced with the label and the value of the corresponding attribute.
     * If a callback (e.g. an anonymous function), the signature must be as follows:
     *
     * ~~~
     * function ($attribute, $index, $widget)
     * ~~~
     *
     * where `$attribute` refer to the specification of the attribute being rendered, `$index` is the zero-based
     * index of the attribute in the [[attributes]] array, and `$widget` refers to this widget instance.
     */
    public $template = '<div class="row static-info">
        <div class="col-md-5 name">{label}</div>
        <div class="col-md-7 value">{value}</div>
        </div>';
}
