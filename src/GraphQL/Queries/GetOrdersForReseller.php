<?php

namespace MayIFit\Extension\Shop\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Storage;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Maatwebsite\Excel\Facades\Excel;

use MayIFit\Extension\Shop\Exports\OrdersExport;
use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;


/**
 * Class GetOrdersForReseller
 *
 * @package MayIFit\Extension\Shop
 */
class GetOrdersForReseller
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $orders =
            OrderProductPivot::whereHas('order', function ($query) use ($args) {
                return $query->when($args['reseller_id'], function ($query) use ($args) {
                    return $query->where(['reseller_id' => $args['reseller_id']]);
                });
            })->whereBetween('created_at', [$args['datetime_from'], $args['datetime_to']])
            ->get();

        $export = new OrdersExport($orders);

        $fileName = 'orders_' . date("Y-m-d") . '.xlsx';

        Excel::store($export, $fileName);

        $path = 'public/exports/' . $fileName;

        if (Storage::exists($path)) {
            Storage::delete($path);
        }

        Storage::move($fileName, $path);

        return 'storage/exports/' . $fileName;
    }

    public function waitingOrders($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $orders =
            OrderProductPivot::whereHas('order', function ($query) use ($args) {
                return $query->when($args['reseller_id'], function ($query) use ($args) {
                    return $query->where(['reseller_id' => $args['reseller_id']]);
                })->whereIn('order_status_id', [1, 3]);
            })->get();

        $export = new OrdersExport($orders);

        $fileName = 'orders_waiting' . date("Y-m-d") . '.xlsx';

        Excel::store($export, $fileName);

        $path = 'public/exports/' . $fileName;

        if (Storage::exists($path)) {
            Storage::delete($path);
        }

        Storage::move($fileName, $path);

        return 'storage/exports/' . $fileName;
    }
}
