<?php
declare(strict_types = 1);

namespace Utils;

class DotEnv
{
    /**
     * Loads one or several .env files.
     *
     * @param string    $path  A file to load
     * @param ...string $paths A list of additional files to load
     *
     * @throws RuntimeException   when a file does not exist or is not readable
     */
    public function load(string $path, string ...$paths): void
    {
        array_unshift($paths, $path);
        foreach ($paths as $path) {
            if (!is_readable($path) || is_dir($path)) {
                throw new \RuntimeException("{$path} is not a readable file.");
            }
            $this->populate(parse_ini_file($path, false));
        }
    }

    /**
         * Insert data in ENV var
         * @param string $path
         */
    public function populate(array $values): void
    {
//    	$loadedVars = array_flip(explode(',', getenv('CHIRON_DOTENV_VARS')));
//        unset($loadedVars['']);

        foreach ($values as $name => $value) {
//            $notHttpName = (0 !== strpos($name, 'HTTP_'));
            // don't check existence with getenv() because of thread safety issues
//            if (!isset($loadedVars[$name]) && (isset($_ENV[$name]) || (isset($_SERVER[$name]) && $notHttpName))) {
//                continue;
//            }
            putenv("$name=$value");
//            $_ENV[$name] = $value;
//            if ($notHttpName) {
//                $_SERVER[$name] = $value;
//            }
//            $loadedVars[$name] = true;
        }

//        if ($loadedVars) {
//            $loadedVars = implode(',', array_keys($loadedVars));
//            putenv("CHIRON_DOTENV_VARS=$loadedVars");
//            $_ENV['CHIRON_DOTENV_VARS'] = $loadedVars;
//            $_SERVER['CHIRON_DOTENV_VARS'] = $loadedVars;
//        }

    }
}
