<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbb5563a9fc0b1401b6d6ff485434da94
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Automattic\\WooCommerce\\' => 23,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Automattic\\WooCommerce\\' => 
        array (
            0 => __DIR__ . '/..' . '/automattic/woocommerce/src/WooCommerce',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbb5563a9fc0b1401b6d6ff485434da94::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbb5563a9fc0b1401b6d6ff485434da94::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitbb5563a9fc0b1401b6d6ff485434da94::$classMap;

        }, null, ClassLoader::class);
    }
}