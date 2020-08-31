<?php

namespace MayIFit\Extension\Shop\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;

/**
 * Class AllPaymentType
 *
 * @package MayIFit\Extension\Shop
 */
class AllPaymentType
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $currencies = DB::table('payment_types')->get();
        return $currencies ?? [];
    }
}
