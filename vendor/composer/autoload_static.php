<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit962ebb62261ce55932c75b8d2b89e567
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twilio\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twilio\\' => 
        array (
            0 => __DIR__ . '/..' . '/twilio/sdk/src/Twilio',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit962ebb62261ce55932c75b8d2b89e567::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit962ebb62261ce55932c75b8d2b89e567::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
