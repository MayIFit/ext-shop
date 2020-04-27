<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use MayIFit\Extension\Shop\Models\Order;

class GetOrderFromToken
{
    /**
     * Try to get Order from token
     * If non was found generate one
     * 
     * @return void
     */
    public static function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) {
        $order = Order::with(['customer.user', 'products'])->where('order_token', $args['token'])->first();
        return $order;
    }
}