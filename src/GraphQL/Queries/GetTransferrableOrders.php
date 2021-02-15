<?php

namespace MayIFit\Extension\Shop\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use MayIFit\Extension\Shop\Models\Order;

/**
 * Class GetTransferrableOrders
 *
 * @package MayIFit\Extension\Shop
 */
class GetTransferrableOrders
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return Order::where([
            ['sent_to_courrier_sevice', '=', NULL],
            ['closed', '=', FALSE],
            ['declined', '=', FALSE],
            $args
        ])->get()->filter(function ($order) {
            return $order->can_be_shipped;
        });
    }
}
