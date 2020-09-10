<?php

namespace MayIFit\Extension\Shop\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Storage;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Maatwebsite\Excel\Facades\Excel;

use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;
use MayIFit\Extension\Shop\Exports\OrdersTransferredExport;

/**
 * Class GetTransferredOrders
 *
 * @package MayIFit\Extension\Shop
 */
class GetTransferredOrders
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $export = new OrdersTransferredExport(
            OrderProductPivot::whereBetween('shipped_at', [$args['datetime_from'], $args['datetime_to']])
                ->with('order', 'order.shippingAddress', 'product')
                ->withTrashed()
                ->orderBy('shipped_at')
                ->get()
        );

        $fileName = 'orders_transferred_' . $args['datetime_from']->format('Y-m-d') . '_' . $args['datetime_to']->format('Y-m-d') . '.xlsx';

        Excel::store($export, $fileName);

        $path = 'public/exports/' . $fileName;

        if (Storage::exists($path)) {
            Storage::delete($path);
        }

        Storage::move($fileName, $path);

        return 'storage/exports/' . $fileName;
    }
}
