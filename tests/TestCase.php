<?php

namespace MayIFit\Extension\Shop\Tests;

use Laravel\Sanctum\SanctumServiceProvider;
use Nuwave\Lighthouse\LighthouseServiceProvider;
use Nuwave\Lighthouse\WhereConditions\WhereConditionsServiceProvider;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

use MayIFit\Core\Permission\PermissionServiceProvider;
use MayIFit\Core\Translation\TranslationServiceProvider;
use MayIFit\Extension\Shop\ShopServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use MakesGraphQLRequests;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->publishResources();
        $this->artisan('migrate', ['--database' => 'testbench'])->execute();
    }

    protected function getPackageProviders($app)
    {
        return [
            SanctumServiceProvider::class,
            LighthouseServiceProvider::class,
            WhereConditionsServiceProvider::class,
            PermissionServiceProvider::class,
            TranslationServiceProvider::class,
            ShopServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->push('lighthouse.namespaces.models', 'Illuminate\\Foundation\\Auth');

        $app['config']->push('app.providers', 'Nuwave\\Lighthouse\\WhereConditions\\WhereConditionsServiceProvider');
    }

    protected function publishResources() {
        $this->artisan('vendor:publish', [
            '--provider' => LighthouseServiceProvider::class,
        ])->execute();

        $this->artisan('vendor:publish', [
            '--provider' => PermissionServiceProvider::class,
        ])->execute();

        $this->artisan('vendor:publish', [
            '--provider' => TranslationServiceProvider::class,
        ])->execute();

        $this->artisan('vendor:publish', [
            '--provider' => ShopServiceProvider::class,
        ])->execute();

        file_put_contents($this->app['config']->get('lighthouse.schema.register'), 
        '
#import core/*.graphql
#import extensions/*.graphql

type Query

type Mutation
        ');
    }
}