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

use Zend\Code\Reflection\ParameterReflection as BaseParameterReflection;

class ParameterReflection extends BaseParameterReflection
{
    public function detectType()
    {
        if (method_exists($this, 'getType')
            && ($type = $this->getType())
            && $type->isBuiltin()
        ) {
            return (string) $type;
        }

        // can be dropped when dropping PHP7 support:
        if ($this->isArray()) {
            return 'array';
        }

        // can be dropped when dropping PHP7 support:
        if ($this->isCallable()) {
            return 'callable';
        }

        if (($class = $this->getClass()) instanceof \ReflectionClass) {
            return $class->getName();
        }

        return null;

        // currenty, ignore docblock..

//        $docBlock = $this->getDeclaringFunction()->getDocBlock();
//
//        if (! $docBlock instanceof DocBlockReflection) {
//            return null;
//        }
//
//        $params = $docBlock->getTags('param');
//
//        if (isset($params[$this->getPosition()])) {
//            return $params[$this->getPosition()]->getType();
//        }
//
//        return null;
    }
}
