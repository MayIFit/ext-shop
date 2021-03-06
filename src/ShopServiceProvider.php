<?php

namespace MayIFit\Extension\Shop;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Console\Scheduling\Schedule;

use MayIFit\Extension\Shop\Providers\EventServiceProvider;
use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductCategory;
use MayIFit\Extension\Shop\Models\ProductCategoryDiscount;
use MayIFit\Extension\Shop\Models\ProductReview;
use MayIFit\Extension\Shop\Models\ProductPricing;
use MayIFit\Extension\Shop\Models\ProductDiscount;
use MayIFit\Extension\Shop\Models\Customer;
use MayIFit\Extension\Shop\Models\Order;
use MayIFit\Extension\Shop\Models\Reseller;
use MayIFit\Extension\Shop\Models\ResellerGroup;
use MayIFit\Extension\Shop\Models\ResellerShopCart;
use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;
use MayIFit\Extension\Shop\Policies\ProductPolicy;
use MayIFit\Extension\Shop\Policies\ProductCategoryPolicy;
use MayIFit\Extension\Shop\Policies\ProductReviewPolicy;
use MayIFit\Extension\Shop\Policies\ProductPricingPolicy;
use MayIFit\Extension\Shop\Policies\ProductDiscountPolicy;
use MayIFit\Extension\Shop\Policies\CustomerPolicy;
use MayIFit\Extension\Shop\Policies\OrderPolicy;
use MayIFit\Extension\Shop\Policies\OrderProductPivotPolicy;
use MayIFit\Extension\Shop\Policies\ResellerPolicy;
use MayIFit\Extension\Shop\Policies\ResellerGroupPolicy;
use MayIFit\Extension\Shop\Policies\ResellerShopCartPolicy;
use MayIFit\Extension\Shop\Observers\ProductObserver;
use MayIFit\Extension\Shop\Observers\ProductPricingObserver;
use MayIFit\Extension\Shop\Observers\ProductDiscountObserver;
use MayIFit\Extension\Shop\Observers\ProductCategoryObserver;
use MayIFit\Extension\Shop\Observers\ProductCategoryDiscountObserver;
use MayIFit\Extension\Shop\Observers\OrderObserver;
use MayIFit\Extension\Shop\Observers\Pivot\OrderProductPivotObserver;
use MayIFit\Extension\Shop\Observers\ResellerObserver;
use MayIFit\Extension\Shop\Observers\ResellerGroupObserver;

use MayIFit\Extension\Shop\Jobs\CollectSendableOrders;
use MayIFit\Extension\Shop\Jobs\ExportTransferredOrders;
use MayIFit\Extension\Shop\Jobs\SyncWarehouseStockFromWMS;
use MayIFit\Extension\Shop\Jobs\SyncOrderStatusFromWMS;


/**
 * Class ShopServiceProvicer
 *
 * @package MayIFit\Extension\Shop
 */
class ShopServiceProvider extends ServiceProvider
{

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
        Reseller::class => ResellerPolicy::class,
        ResellerGroup::class => ResellerGroupPolicy::class,
        OrderProductPivot::class => OrderProductPivotPolicy::class,
        ResellerShopCart::class => ResellerShopCartPolicy::class
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
    public function boot(ConfigRepository $configRepository): void
    {
        Relation::morphMap([
            'product' => 'MayIFit\Extension\Shop\Models\Product',
            'product_category' => 'MayIFit\Extension\Shop\Models\ProductCategory',
            'user' => 'App\Models\User',
        ]);
        $this->mergeConfigFrom(__DIR__ . '/ext-shop.php', 'ext-shop');
        $this->loadMigrationsFrom(__DIR__ . $this->database_folder . '/migrations');
        $this->loadFactoriesFrom(__DIR__ . $this->database_folder . '/Factories');
        $this->publishResources($configRepository);
        $this->registerPolicies();

        $this->registerObservers();

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->job(new CollectSendableOrders, 'order_transfer')->weekdays()->dailyAt('14:00');
            $schedule->job(new ExportTransferredOrders, 'email')->weekdays()->dailyAt('14:30');
            $schedule->job(new SyncWarehouseStockFromWMS, 'sync')->weekdays()->dailyAt('23:00');
            $schedule->job(new SyncOrderStatusFromWMS, 'sync')->weekdays()->hourly();
            $schedule->command('queue:restart')->hourly();
            $schedule->command('queue:work --sleep=3 --timeout=900 --queue=order_transfer,sync,email')->runInBackground()->withoutOverlapping()->everyMinute();
        });
    }

    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Publish resources
     *
     * @return void
     */
    protected function publishResources(ConfigRepository $configRepository): void
    {
        $this->publishes([
            __DIR__ . '/ext-shop.php' => $this->app->configPath() . '/ext-shop.php',
        ], 'config');

        $this->publishes([
            __DIR__ . '/GraphQL/schema' => $configRepository->get('ext-shop.schema.register'),
        ], 'schema');

        $this->publishes([
            __DIR__ . '/GraphQL/Queries' => $configRepository->get('ext-shop.queries.register'),
        ], 'graphql');

        $this->publishes([
            __DIR__ . '/GraphQL/Mutations' => $configRepository->get('ext-shop.mutations.register'),
        ], 'graphql');
    }

    /**
     * Register model observers.
     *
     * @return void
     */
    private function registerObservers(): void
    {
        Product::observe(ProductObserver::class);
        ProductPricing::observe(ProductPricingObserver::class);
        ProductDiscount::observe(ProductDiscountObserver::class);
        ProductCategory::observe(ProductCategoryObserver::class);
        ProductCategoryDiscount::observe(ProductCategoryDiscountObserver::class);
        Order::observe(OrderObserver::class);
        OrderProductPivot::observe(OrderProductPivotObserver::class);
        Reseller::observe(ResellerObserver::class);
        ResellerGroup::observe(ResellerGroupObserver::class);
    }
}
