<?php

namespace MayIFit\Extension\Shop\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use MayIFit\Extension\Shop\Models\Order;

/**
 * Class GetResellerLastOpenOrder
 *
 * @package MayIFit\Extension\Shop
 */
class GetResellerLastOpenOrder
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) {
        return Order::where('reseller_id', $context->user->reseller->id)
            ->whereNull('sent_to_courier_service')
            ->where('order_id_prefix', 'NOT LIKE', '%EXT%')
            ->orderBy('id', 'DESC')
            ->first();
    }
}