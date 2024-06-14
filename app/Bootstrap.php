<?php

declare(strict_types=1);

namespace App;

use Nette\Configurator;
use Tracy\Debugger;

class Bootstrap
{
    private static function boot(string $site): Configurator
    {
        $configurator = new Configurator();

        if (getenv('NETTE_DEVEL') === '1') {
            $configurator->setDebugMode(true);
        }
        $configurator->enableTracy(__DIR__ . '/../log');
        if (getenv('NETTE_DEVEL') !== '1') {
            Debugger::enable(Debugger::Production);
        }
        error_reporting(~E_USER_DEPRECATED);
        $configurator->setTimeZone('Europe/Prague');
        $configurator->setTempDirectory(__DIR__ . '/../temp/' . $site);

        $configurator->createRobotLoader()
            ->addDirectory(__DIR__)
            ->register();

        $configurator->addConfig(__DIR__ . '/config/config.' . $site . '.neon');
        $configurator->addConfig(__DIR__ . '/config/local/' . $site . '.neon');

        return $configurator;
    }

    public static function bootFof(): Configurator
    {
        return self::boot('fof');
    }

    public static function bootFol(): Configurator
    {
        return self::boot('fol');
    }

    public static function bootDsef(): Configurator
    {
        return self::boot('dsef');
    }

    public static function bootFykos(): Configurator
    {
        return self::boot('fykos');
    }

    public static function bootVyfuk(): Configurator
    {
        return self::boot('vyfuk');
    }
}
