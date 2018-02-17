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

use Zend\Code\Reflection\FunctionReflection as BaseFunctionReflection;

class FunctionReflection extends BaseFunctionReflection
{
    public function getDocBlock()
    {
        if ('' == ($comment = $this->getDocComment())) {
//            throw new Exception\InvalidArgumentException(sprintf(
//                '%s does not have a DocBlock',
//                $this->getName()
//            ));
            return '';
        }

        $instance = new DocBlockReflection($comment);

        return $instance;
    }

    public function getParameters()
    {
        $phpReflections  = parent::getParameters();
        $zendReflections = [];
        while ($phpReflections && ($phpReflection = array_shift($phpReflections))) {
            $instance          = new ParameterReflection($this->getName(), $phpReflection->getName());
            $zendReflections[] = $instance;
            unset($phpReflection);
        }
        unset($phpReflections);

        return $zendReflections;
    }

    public function getPrototype($format = FunctionReflection::PROTOTYPE_AS_ARRAY)
    {
        $returnType = 'mixed';
        if ($this->hasReturnType()) {
            $reflectReturn = $this->getReturnType();
            if ($reflectReturn->allowsNull()) {
                $returnType = sprintf("?%s", $reflectReturn->getName());
            } else {
                $returnType = $reflectReturn->getName();
            }
        }

        $docBlock = $this->getDocBlock();
        if ($returnType === 'mixed' && $docBlock) {
            $return = $docBlock->getTag('return');
            $returnTypes = $return->getTypes();
            $returnType = count($returnTypes) > 1 ? implode('|', $returnTypes) : $returnTypes[0];
        }

        $prototype = [
            'namespace' => $this->getNamespaceName(),
//            'name'      => substr($this->getName(), strlen($this->getNamespaceName()) + 1),
            'name'      => substr($this->getName(), strlen($this->getNamespaceName()) + ($this->getNamespaceName() ? 1 : 0)),
            'return'    => $returnType,
            'arguments' => [],
        ];

        $parameters = $this->getParameters();
        foreach ($parameters as $parameter) {
            $prototype['arguments'][$parameter->getName()] = [
                'type'     => $parameter->detectType(),
                'required' => !$parameter->isOptional(),
                'by_ref'   => $parameter->isPassedByReference(),
                'default'  => $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null,
            ];
        }

        if ($format == FunctionReflection::PROTOTYPE_AS_STRING) {
            $line = $prototype['return'] . ' ' . $prototype['name'] . '(';
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
            $line .= ')';

            return $line;
        }

        return $prototype;
    }
}
