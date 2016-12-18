<?php
declare(strict_types=1);

/**
 * most of parts is borrowed from zendframework/zend-code
 * https://github.com/zendframework/zend-code
 *
 * This source is aimed for hack to override zend-code.
 *
 * @license New BSD, code from Zend Framework
 * https://github.com/zendframework/zend-code/blob/master/LICENSE.md
 */

namespace PHPExtensionStubGenerator\ZendCode;

class FunctionGenerator
{
    public static function generateByPrototypeArray(array $prototype)
    {
        $line = 'function' . ' ' . $prototype['name'] . '(';
        $args = [];
        foreach ($prototype['arguments'] as $name => $argument) {
            $argsLine = ($argument['type']
                    ? $argument['type'] . ' '
                    : '') . ($argument['by_ref'] ? '&' : '') . '$' . $name;
            if (!$argument['required']) {
                $argsLine .= ' = ' . var_export($argument['default'], true);
            }
            $args[] = $argsLine;
        }
        $line .= implode(', ', $args);
        $line .= '){}';

        return $line;
    }

}