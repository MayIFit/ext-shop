<?php
    namespace MayIFit\Extension\Shop;

    use Illuminate\Contracts\Config\Repository as ConfigRepository;
    use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
    use Illuminate\Database\Eloquent\Relations\Relation;

    use MayIFit\Extension\Shop\Providers\EventServiceProvider;
    use MayIFit\Extension\Shop\Models\Product;
    use MayIFit\Extension\Shop\Models\ProductCategory;
    use MayIFit\Extension\Shop\Models\ProductReview;
    use MayIFit\Extension\Shop\Models\ProductPricing;
    use MayIFit\Extension\Shop\Models\ProductDiscount;
    use MayIFit\Extension\Shop\Models\Customer;
    use MayIFit\Extension\Shop\Models\Order;
    use MayIFit\Extension\Shop\Models\Reseller;
    use MayIFit\Extension\Shop\Policies\ProductPolicy;
    use MayIFit\Extension\Shop\Policies\ProductCategoryPolicy;
    use MayIFit\Extension\Shop\Policies\ProductReviewPolicy;
    use MayIFit\Extension\Shop\Policies\ProductPricingPolicy;
    use MayIFit\Extension\Shop\Policies\ProductDiscountPolicy;
    use MayIFit\Extension\Shop\Policies\CustomerPolicy;
    use MayIFit\Extension\Shop\Policies\OrderPolicy;
    use MayIFit\Extension\Shop\Policies\ResellerPolicy;

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
            ProductCategory::class => ProductCategoryPolicy::class,
            ProductReview::class => ProductReviewPolicy::class,
            ProductPricing::class => ProductPricingPolicy::class,
            ProductDiscount::class => ProductDiscountPolicy::class,
            Reseller::class => ResellerPolicy::class
        ];

        /**
         * The seed folder for the package.
         *
         * @var array
         */
        protected $database_folder = '/Database';

        /**
         * Bootstrap any application services.
         */
        public function boot(ConfigRepository $configRepository): void {
            Relation::morphMap([
                'product' => 'MayIFit\Extension\Shop\Models\Product',
                'product_category' => 'MayIFit\Extension\Shop\Models\ProductCategory',
            ]);
            $this->mergeConfigFrom(__DIR__.'/ext-shop.php', 'ext-shop');
            $this->loadMigrationsFrom(__DIR__.$this->database_folder.'/migrations');
            $this->loadFactoriesFrom(__DIR__.$this->database_folder.'/Factories');
            $this->publishResources($configRepository);
            $this->registerPolicies();
        }

        public function register(): void {
            $this->app->register(EventServiceProvider::class);
        }

        /**
         * Publish resources
         *
         * @return void
         */
        protected function publishResources(ConfigRepository $configRepository): void {
            $this->publishes([
                __DIR__.'/ext-shop.php' => $this->app->configPath().'/ext-shop.php',
            ], 'config');

            $this->publishes([
                __DIR__.'/GraphQL/schema' => $configRepository->get('ext-shop.schema.register'),
            ], 'schema');

            $this->publishes([
                __DIR__.'/GraphQL/Queries' => $configRepository->get('ext-shop.queries.register'),
            ], 'graphql');
        }
    }
?>