<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Factory;

function bootstrap()
{
    $dbPath = __DIR__ . '/../project3_base.db';
    touch($dbPath);

    $capsule = new Capsule();
    $capsule->addConnection([
        'driver'    => 'sqlite',
        'database'  => $dbPath,
        // 'username'  => 'root',
        // 'password'  => 'password',
        /* 'charset'   => 'utf8', */
        /* 'collation' => 'utf8_unicode_ci', */
        /* 'prefix'    => '', */
    ]);
    // Make this Capsule instance available globally via static methods... (optional)
    $capsule->setAsGlobal();

    $capsule->setEventDispatcher(new Dispatcher(new Container()));

    // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
    $capsule->bootEloquent();

    $capsule->connection()->listen(function ($query) {
        echo "\n";
        var_dump($query->sql);
    });

    $faker = \Faker\Factory::create();
    $factory = loadFactories($faker);

    return ['capsule' => $capsule, 'factory' => $factory, 'faker' => $faker];
}

function loadFactories($faker)
{
    return Factory::construct($faker, __DIR__ . '/../factories');
}

$domen = new \App\Domain();

bootstrap();

$domen->name = 'dss@naim.cds';
$domen->save();

dd($domen->toArray());
