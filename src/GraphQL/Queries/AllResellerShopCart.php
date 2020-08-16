<?php

namespace MayIFit\Extension\Shop\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use MayIFit\Extension\Shop\Models\ResellerShopCart;

class AllResellerShopCart
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) {
        return ResellerShopCart::where('reseller_id', $context->user->reseller->id ?? null)->get();
    }
}