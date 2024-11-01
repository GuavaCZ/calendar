<?php

namespace Guava\Calendar\Tests;

use Guava\Calendar\CalendarServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Guava\\Calendar\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            CalendarServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_calendar_table.php.stub';
        $migration->up();
        */
    }
}
