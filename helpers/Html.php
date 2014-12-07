<?php
/**
 * @link http://yii2metronic.icron.org/
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace yii\helpers;

/**
 * Html helper
 */
class Html extends BaseHtml
{
    /**
     * Generates a link tag that refers to an external CSS file.
     * @param array|string $url the URL of the external CSS file. This parameter will be processed by [[\yii\helpers\Url::to()]].
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [[encode()]].
     * If a value is null, the corresponding attribute will not be rendered.
     * See [[renderTagAttributes()]] for details on how attributes are being rendered.
     * @return string the generated link tag
     * @see \yii\helpers\Url::to()
     */
    public static function cssFile($url, $options = [])
    {
        if (!isset($options['rel'])) {
            $options['rel'] = 'stylesheet';
        }
        $options['href'] = Url::to($url);
        if (!empty($options['conditions'])) {
            foreach ($options['conditions'] as $file => $condition) {
                if (strpos($url, $file) !== false) {
                    unset($options['conditions']);
                    return static::conditionalComment(static::tag('link', '', $options), $condition);
                }
            }
        }
        unset($options['conditions']);

        return static::tag('link', '', $options);
    }

    /**
     * Generates a script tag that refers to an external JavaScript file.
     * @param  string $url     the URL of the external JavaScript file. This parameter will be processed by [[\yii\helpers\Url::to()]].
     * @param  array  $options the tag options in terms of name-value pairs. These will be rendered as
     *                         the attributes of the resulting tag. The values will be HTML-encoded using [[encode()]].
     *                         If a value is null, the corresponding attribute will not be rendered.
     *                         See [[renderTagAttributes()]] for details on how attributes are being rendered.
     * @return string the generated script tag
     * @see \yii\helpers\Url::to()
     */
    public static function jsFile($url, $options = [])
    {
        $options['src'] = Url::to($url);
        if (!empty($options['conditions'])) {
            foreach ($options['conditions'] as $file => $condition) {
                if (strpos($url, $file) !== false) {
                    unset($options['conditions']);
                    return static::conditionalComment(static::tag('script', '', $options), $condition);
                }
            }
        }
        unset($options['conditions']);

        return static::tag('script', '', $options);
    }

    /**
     * Generates conditional comments such as '<!--[if...]>' or '<!--[if...]<!-->'.
     * @param $content string the commented content
     * @param $condition string condition. Can contain 'if...' or '<!--[if...]<!-->'
     * @return string the generated result
     */
    public static function conditionalComment($content, $condition)
    {
        $condition = strpos($condition, '<!--') !== false ? $condition : '<!--[' . $condition . ']>';
        $lines = [];
        $lines[] = $condition;
        $lines[] = $content;
        $lines[] = (strpos($condition, '-->') !== false ? '<!--' : '') . '<![endif]-->';

        return implode("\n", $lines);
    }
}