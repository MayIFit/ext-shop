<?php

namespace MayIFit\Extension\Shop\GraphQL\Queries;

use Illuminate\Support\Facades\DB;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Class GetOrderTrend
 *
 * @package MayIFit\Extension\Shop
 */
class GetOrderTrend
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return DB::table('orders')
            ->select(DB::raw('CAST(created_at as date) as date'), DB::raw('count(*) as orders'), DB::raw('SUM(net_value) as net_value'), DB::raw('SUM(gross_value) as gross_value'))
            ->whereBetween('created_at', [$args['datetime_from'], $args['datetime_to']])
            ->whereNull('deleted_at')
            ->groupBy(DB::raw('CAST(created_at as date)'))
            ->get();
    }

    public function transferredOrders($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return DB::table('orders')
            ->select(DB::raw('CAST(created_at as date) as date'), DB::raw('count(*) as orders'))
            ->whereBetween('sent_to_courier_service', [$args['datetime_from'], $args['datetime_to']])
            ->whereNull('deleted_at')
            ->groupBy(DB::raw('CAST(created_at as date)'))
            ->get();
    }
}
