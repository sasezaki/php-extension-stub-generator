<?php
namespace PHPReRewindTest;

use PHPExtensionStubGenerator\FilesDumper;
use ReflectionExtension;
use PHPUnit_Framework_TestCase;

class FilesDumperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var FilesDumper
     */
    private $generator;

    private $tmpDir;

    public function setUp()
    {
        $this->generator = new FilesDumper(new ReflectionExtension('mbstring'));
    }

    public function testDumpFiles()
    {
        $this->markTestIncomplete();
        $this->generator->dumpFiles($this->tmpDir);
    }

    public function testGenerateConstants()
    {
        $this->markTestIncomplete();
        $constants = $this->generator->generateConstants();
    }
}
