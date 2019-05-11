<?php
declare(strict_types=1);

namespace PHPExtensionStubGenerator\Console;

use Iterator;
use ReflectionExtension;
use Zend\Console\Adapter\AdapterInterface;
use PHPExtensionStubGenerator\FilesDumper as BaseFilesDumper;

class FilesDumper extends BaseFilesDumper
{
    private $console;

    public function __construct(ReflectionExtension $reflectionExtension, AdapterInterface $console)
    {
        parent::__construct($reflectionExtension);
        $this->console = $console;
    }

    protected function getGenerationTargets() : Iterator
    {
        foreach (parent::getGenerationTargets() as $file => $code) {
            $this->console->writeLine($file);
            yield $file => $code;
        }
    }
}