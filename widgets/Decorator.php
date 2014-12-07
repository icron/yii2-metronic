<?php
/**
 * @link http://yii2metronic.icron.org/
 * @copyright Copyright (c) 2014 icron.org
 * @license http://yii2metronic.icron.org/license.html
 */

namespace icron\metronic\widgets;

use yii\base\InvalidCallException;
use yii\base\Object;

/**
 * Decorator outputs buffering content between [[begin()]] and [[end()]]
 */
class Decorator extends Object
{
    /**
     * @var array this property is maintained by [[begin()]] and [[end()]] methods.
     * @internal
     */
    public static $stack = [];

    /**
     * Turn on output buffering
     */
    public static  function begin()
    {
        self::$stack[] = get_called_class();
        ob_start();
        ob_implicit_flush(false);
    }

    /**
     * Get current buffer contents and delete current output buffer.
     * @return string the content between [[begin()]] and [[end()]]
     * @throws InvalidCallException if [[begin()]] and [[end()]] calls are not properly nested
     */
    public static function end()
    {
        if (!empty(self::$stack)) {
           return ob_get_clean();
        } else {
            throw new InvalidCallException("Unexpected " . get_called_class() . '::end() call. A matching begin() is not found.');
        }
    }
}