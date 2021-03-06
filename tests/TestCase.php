<?php

namespace MayIFit\Extension\Shop\Tests;

use Laravel\Sanctum\Sanctum;
use Laravel\Sanctum\SanctumServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Nuwave\Lighthouse\LighthouseServiceProvider;
use Nuwave\Lighthouse\WhereConditions\WhereConditionsServiceProvider;
use Nuwave\Lighthouse\OrderBy\OrderByServiceProvider;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

use MayIFit\Core\Permission\PermissionServiceProvider;
use MayIFit\Core\Translation\TranslationServiceProvider;
use MayIFit\Extension\Shop\ShopServiceProvider;
use MayIFit\Extension\Shop\Tests\User;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use MakesGraphQLRequests;

    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'testbench'])->run();
        $this->loadLaravelMigrations(['--database' => 'testbench']);
        $this->publishResources();
        $this->withFactories(__DIR__ . '../src/Database/Factories');
        $this->withFactories(__DIR__ . '/Factories');

        // As almost every model has a createdBy or updatedBy relation
        // it makes sense to create a mock user here, globally
        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);

        Relation::morphMap([
            'user' => 'MayIFit\Extension\Shop\Tests\User',
        ]);

        $this->artisan('db:seed', ['--database' => 'testbench', '--class' => 'MayIFit\\Extension\\Shop\\Database\\Seeds\\DatabaseSeeder'])->run();
    }

    /**
     * Get package providers.  At a minimum this is the package being tested, but also
     * would include packages upon which our package depends, e.g. Cartalyst/Sentry
     * In a normal app environment these would be added to the 'providers' array in
     * the config/app.php file.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            SanctumServiceProvider::class,
            WhereConditionsServiceProvider::class,
            OrderByServiceProvider::class,
            LighthouseServiceProvider::class,
            PermissionServiceProvider::class,
            TranslationServiceProvider::class,
            ShopServiceProvider::class
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->push('lighthouse.namespaces.models', 'MayIFit\\Extension\\Shop\\Tests');
    }

    protected function publishResources(): void
    {
        $this->artisan('vendor:publish', [
            '--provider' => LighthouseServiceProvider::class,
            '--force' => true
        ])->execute();

        $this->artisan('vendor:publish', [
            '--provider' => PermissionServiceProvider::class,
            '--tag' => 'schema',
            '--force' => true,
        ])->execute();

        $this->artisan('vendor:publish', [
            '--provider' => TranslationServiceProvider::class,
            '--tag' => 'schema',
            '--force' => true,
        ])->execute();

        $this->artisan('vendor:publish', [
            '--provider' => ShopServiceProvider::class,
            '--tag' => 'schema',
            '--force' => true,
        ])->execute();


        file_put_contents(
            $this->app['config']->get('lighthouse.schema.register'),
            '
#import core/*.graphql
#import extensions/*.graphql

type User {
    id: ID!
    name: String
    email: String!
}

input CreateUserInput {
    name: String!
    email: String!
    password: String! @hash
}

input UpdateUserInput {
    id: ID!
    name: String
    email: String
    password: String @hash
}

type Query

type Mutation
        ',
            FILE_APPEND
        );
    }
}
