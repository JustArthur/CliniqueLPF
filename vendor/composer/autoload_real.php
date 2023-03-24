<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit7986744b4a4bc4316663f4ca6c7c9dfa
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit7986744b4a4bc4316663f4ca6c7c9dfa', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit7986744b4a4bc4316663f4ca6c7c9dfa', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit7986744b4a4bc4316663f4ca6c7c9dfa::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
