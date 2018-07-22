<?php
namespace PHPReRewindTest;

use ReflectionExtension;
use PHPUnit\Framework\TestCase;
use PHPExtensionStubGenerator\FilesDumper;

class FilesDumperTest extends TestCase
{
    /**
     * @var FilesDumper
     */
    private $generator;

    private static $tmpDir;

    public static function setUpBeforeClass()
    {
        self::$tmpDir = sys_get_temp_dir() . '/php-extension-stub-generator';
    }

    public function setUp()
    {
        $this->generator = new FilesDumper(new ReflectionExtension('mbstring'));
    }

    public function testDumpFiles()
    {
        $this->generator->dumpFiles(self::$tmpDir);
        $this->assertTrue(file_exists(sprintf(FilesDumper::FUNCTIONS_FILENAME, self::$tmpDir)));
        $this->assertTrue(file_exists(sprintf(FilesDumper::CONST_FILENAME, self::$tmpDir)));
    }
}
