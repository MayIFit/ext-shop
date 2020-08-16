<?php

namespace MayIFit\Extension\Shop\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use MayIFit\Extension\Shop\Models\Order;

class GetResellerLastOpenOrder
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) {
        return Order::where('reseller_id', $context->user->reseller->id)
            ->whereNull('sent_to_courier_service')
            ->orderBy('id', 'DESC')
            ->first();
    }
}