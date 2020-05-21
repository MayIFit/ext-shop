<?php

namespace App\GraphQL\Queries\Extensions;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use MayIFit\Extension\Shop\Models\Order;

class GetOrderFromToken
{
    // TODO: fix product pricing saving
    /**
     * Try to get Order from token
     * 
     * 
     * @return void
     */
    public static function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) {
        $order = Order::with(['customer.user', 'products'])->where('token', $args['token'])->first();
        return $order;
    }
}