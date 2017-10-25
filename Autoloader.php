<?php

class Autoloader
{
    private static $loadedFilename;
    public static function loadPackages($className)
    {
        $pathParts = explode('\\', $className);
        self::$loadedFilename = implode(DIRECTORY_SEPARATOR, $pathParts) . '.php';
        if (file_exists(self::$loadedFilename)) {
            require_once(self::$loadedFilename);
        }
    }
}
spl_autoload_register(array('Autoloader', 'loadPackages'));
