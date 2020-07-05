<?php

namespace MayIFit\Extension\Shop\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;

class AllCurrency
{
    public function __invoke($rootValue,array $args, GraphQLContext $context, ResolveInfo $resolveInfo) {
        $currencies = DB::table('currencies')->get();
        return $currencies ?? [];
    }
}