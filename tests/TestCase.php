<?php

namespace Guava\Calendar\Tests;

use Filament\Support\SupportServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

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
        // Filament's support layer wires into Livewire during boot, so Livewire must be
        // registered first. This is enough to exercise the value objects and the
        // FilamentTimezone facade without booting a full panel.
        return [
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('app.timezone', 'UTC');
        config()->set('app.key', 'base64:'.base64_encode('guava-calendar-testing-key-32byte'));
    }
}
