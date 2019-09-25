<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit04a71eaab35a238419a67cb1ca0fda2e
{
    public static $files = array (
        '2a1181a15c0b875073a40ff3b11f1688' => __DIR__ . '/../..' . '/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Subway\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Subway\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit04a71eaab35a238419a67cb1ca0fda2e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit04a71eaab35a238419a67cb1ca0fda2e::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}