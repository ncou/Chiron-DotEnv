<?php
declare(strict_types = 1);

namespace Chiron\Utils;

use InvalidArgumentException;

/**
 * Manages .env files.
 *
 */
class DotEnv
{
    /**
     * Loads one or several .env files.
     *
     * @param string    $path  A file to load
     * @param ...string $paths A list of additional files to load
     *
     * @throws InvalidArgumentException   when a file does not exist or is not readable
     */
    public function load(string $path, string ...$paths): void
    {
        array_unshift($paths, $path);
        foreach ($paths as $path) {
            if (!is_readable($path) || is_dir($path) || ($contents = parse_ini_file($path, false)) === false) {
                throw new InvalidArgumentException("{$path} is not a readable file.");
            }
            $this->populate($contents);
        }
    }
    /**
     * Sets values as environment variables (via putenv, and don't touch to : $_ENV, and $_SERVER).
     *
     * Note that existing environment variables are not overridden by default.
     *
     * @param array $values An array of env variables
     * @param bool $overwrite Choose if we override the value
     */
    public function populate(array $values, bool $overwrite = false): void
    {
        foreach ($values as $name => $value) {
            if (getenv($name) !== false && !$overwrite) {
                continue;
            }
            putenv("$name=$value");
        }
    }
}
