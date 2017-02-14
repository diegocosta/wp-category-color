<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit39c3b3ace92ebc0ad5457ba873ef8d48
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'DiegoCosta\\WP\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'DiegoCosta\\WP\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit39c3b3ace92ebc0ad5457ba873ef8d48::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit39c3b3ace92ebc0ad5457ba873ef8d48::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
