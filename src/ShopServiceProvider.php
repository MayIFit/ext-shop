<?php
    namespace MayIFit\Extension\Shop;

    use Illuminate\Console\Events\CommandFinished;
    use Illuminate\Support\Facades\Artisan;
    use Illuminate\Support\Facades\Event;
    use Illuminate\Support\Facades\Request;
    use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
    use Symfony\Component\Console\Output\ConsoleOutput;
    use Illuminate\Database\Eloquent\Relations\Relation;


    use MayIFit\Extension\Shop\Models\Product;
    use MayIFit\Extension\Shop\Models\ProductCategory;
    use MayIFit\Extension\Shop\Models\Customer;
    use MayIFit\Extension\Shop\Models\Order;
    use MayIFit\Extension\Shop\Policies\ProductPolicy;
    use MayIFit\Extension\Shop\Policies\ProductCategoryPolicy;
    use MayIFit\Extension\Shop\Policies\CustomerPolicy;
    use MayIFit\Extension\Shop\Policies\OrderPolicy;

    class ShopServiceProvider extends ServiceProvider {

        /**
         * The policy mappings for the application.
         *
         * @var array
         */
        protected $policies = [
            Product::class => ProductPolicy::class,
            Customer::class => CustomerPolicy::class,
            Order::class => OrderPolicy::class,
            ProductCategory::class => ProductCategoryPolicy::class
        ];

        /**
         * The seed folder for the package.
         *
         * @var array
         */
        protected $database_folder = '/Database';

        public function boot() {
            Relation::morphMap([
                'product' => 'MayIFit\Extension\Shop\Models\Product',
                'product_category' => 'MayIFit\Extension\Shop\Models\ProductCategory',
            ]);

            $this->loadMigrationsFrom(__DIR__.$this->database_folder.'/migrations');
            $this->loadFactoriesFrom(__DIR__.$this->database_folder.'/Factories');
            if ($this->app->runningInConsole()) {
                if ($this->isConsoleCommandContains([ 'db:seed', '--seed' ], [ '--class', 'help', '-h' ])) {
                    $this->addSeedsAfterConsoleCommandFinished();
                }
            }
            $this->publishes([
                __DIR__.'/GraphQL/schema' => './graphql/extensions',
            ]);
            $this->publishes([
                __DIR__.'/GraphQL/Queries' => './app/GraphQL/Queries/Extensions',
            ]);
            $this->registerPolicies();
        }

        public function register() {
            $this->app->bind('product', function () {
                return new Product();
            });
            $this->app->bind('customer', function () {
                return new Customer();
            });
            $this->app->bind('order', function () {
                return new Order();
            });
        }

        /**
         * Get a value that indicates whether the current command in console
         * contains a string in the specified $fields.
         *
         * @param string|array $contain_options
         * @param string|array $exclude_options
         *
         * @return bool
         */
        protected function isConsoleCommandContains($contain_options, $exclude_options = null) : bool {
            $args = Request::server('argv', null);
            if (is_array($args)) {
                $command = implode(' ', $args);
                if (str_contains($command, $contain_options) && ($exclude_options == null || !str_contains($command, $exclude_options))) {
                    return true;
                }
            }
            return false;
        }

        /**
         * Add seeds from the $seed_path after the current command in console finished.
         */
        protected function addSeedsAfterConsoleCommandFinished() {
            Event::listen(CommandFinished::class, function(CommandFinished $event) {
                // Accept command in console only,
                // exclude all commands from Artisan::call() method.
                if ($event->output instanceof ConsoleOutput) {
                    Artisan::call('db:seed', [ '--class' => "MayIFit\Extension\Shop\Database\Seeds\DatabaseSeeder", '--force' => '' ]);
                }
            });
        }
    }
?>