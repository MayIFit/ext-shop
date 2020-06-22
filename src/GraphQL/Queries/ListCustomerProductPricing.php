<?php

namespace App\GraphQL\Queries\Extensions;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use MayIFit\Extension\Shop\Models\ProductPricing;

class ListCustomerProductPricing
{
    /**
     * List all pricings for Reseller
     * 
     * @return void
     */
    public static function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) {
        $order = ProductPricing::with(['reseller.user', 'products'])->where('reseller_id', '=', $args['reseller_id'])->paginate();
        return $order;
    }
}