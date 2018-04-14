<?php

namespace Chiron\Dotenv\Tests;

use PHPUnit\Framework\TestCase;
use Chiron\Utils\Dotenv;

class DotenvTest extends TestCase
{
    public function testLoad()
    {
        putenv('FOO');
        putenv('BAR');
        @mkdir($tmpdir = sys_get_temp_dir().'/dotenv');
        $path1 = tempnam($tmpdir, 'chiron-');
        $path2 = tempnam($tmpdir, 'chiron-');
        file_put_contents($path1, 'FOO=BAR');
        file_put_contents($path2, 'BAR=BAZ');
        (new DotEnv())->load($path1, $path2);
        $foo = getenv('FOO');
        $bar = getenv('BAR');
        putenv('FOO');
        putenv('BAR');
        unlink($path1);
        unlink($path2);
        @rmdir($tmpdir);
        $this->assertSame('BAR', $foo);
        $this->assertSame('BAZ', $bar);
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadDirectory()
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__);
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadWithFormatError()
    {
        @mkdir($tmpdir = sys_get_temp_dir().'/dotenv');
        $path1 = tempnam($tmpdir, 'chiron-');
        file_put_contents($path1, 'FOO=&');
        (new DotEnv())->load($path1);
    }

    public function testEnvVarIsNotOverriden()
    {
        putenv('TEST_ENV_VAR=original_value');
        $dotenv = new DotEnv();
        $dotenv->populate(array('TEST_ENV_VAR' => 'new_value'), false);
        $this->assertSame('original_value', getenv('TEST_ENV_VAR'));
    }

    public function testEnvVarIsOverriden()
    {
        putenv('TEST_ENV_VAR=original_value');
        $dotenv = new DotEnv();
        $dotenv->populate(array('TEST_ENV_VAR' => 'new_value'), true);
        $this->assertSame('new_value', getenv('TEST_ENV_VAR'));
    }
}
