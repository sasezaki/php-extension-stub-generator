<?php
declare(strict_types=1);

namespace PHPExtensionStubGenerator;

use Generator;
use PHPExtensionStubGenerator\ZendCode\FunctionGenerator;
use ReflectionExtension;
use Zend\Code\Generator\ {
    ClassGenerator, DocBlockGenerator
};
use Zend\Code\Reflection\ClassReflection;
use PHPExtensionStubGenerator\ZendCode\FunctionReflection;

class GeneratorDumper
{
    const CONST_FILENAME = '%s/const.php';
    const FUNCTIONS_FILENAME = '%s/functions.php';
    const CLASS_FILENAME = '%s.php';

    private $reflectionExtension;
    private $docBlockGenerator;

    public function __construct(ReflectionExtension $reflectionExtension)
    {
        $this->reflectionExtension = $reflectionExtension;
    }

    public function getGenerates() : Generator
    {
        yield from $this->generateConstants();
        yield from $this->generateFunctions();
        yield from $this->generateClasses();
    }


    public function setDocBlockGenerator(DocBlockGenerator $docBlockGenerator) : void
    {
        $this->docBlockGenerator = $docBlockGenerator;
    }

    public function getDocBlockGenerator() : DocBlockGenerator
    {
        if (!$this->docBlockGenerator instanceof DocBlockGenerator) {
            $docBlockGenerator = new DocBlockGenerator('auto generated file by PHPExtensionStubGenerator');
            $this->docBlockGenerator = $docBlockGenerator;
        }

        return $this->docBlockGenerator;
    }

    public function generateConstants() : Generator
    {
        $reflectionConstants = $this->reflectionExtension->getConstants();

        $declaredNamespaces = [];
        foreach ($reflectionConstants as $constant => $value) {
            $c = preg_split('#\\\#', $constant);

            // has namespace ?
            if (count($c) > 1) {
                list($namespaces, $constName) = array_chunk($c, count($c)-1);
                $constName = current($constName);

                $namespace = implode('\\', $namespaces);
                if (!isset($declaredNamespaces[$namespace])) {
                    $declaredNamespaces[$namespace] = true;
                    yield "namespace {$namespace};";
                }

                $encodeValue = is_string($value) ? sprintf('"%s"', $value) : $value;
                yield "const $constName = {$encodeValue};";
            } else {
                $encodeValue = is_string($value) ? sprintf('"%s"', $value) : $value;
                yield "const $constant = {$encodeValue};";
            }
        }

        return "";
    }

    public function generateClasses() : Generator
    {
        /** @var \ReflectionClass $phpClassReflection */
        foreach ($this->reflectionExtension->getClasses() as $fqcn => $phpClassReflection) {
            $classGenerator = ClassGenerator::fromReflection(new ClassReflection($phpClassReflection->getName()));

            yield $classGenerator->generate();
        }

        return "";
    }

    public function generateFunctions() : Generator
    {
        $declaredNamespaces = [];
        foreach ($this->reflectionExtension->getFunctions() as $function_name => $phpFunctionReflection) {

            $functionReflection = new FunctionReflection($function_name);

            $namespace = $functionReflection->getNamespaceName();
            if ($namespace && !isset($declaredNamespaces[$namespace])) {
                $declaredNamespaces[$namespace] = true;
                yield "namespace {$namespace};";
            }

            yield FunctionGenerator::generateByPrototypeArray($functionReflection->getPrototype());
        }

        return "";
    }

}
