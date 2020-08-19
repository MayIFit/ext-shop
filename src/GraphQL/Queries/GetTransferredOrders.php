<?php

namespace MayIFit\Extension\Shop\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Storage;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Maatwebsite\Excel\Facades\Excel;

use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;
use MayIFit\Extension\Shop\Exports\OrdersTransferredExport;

class GetTransferredOrders
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) {
        $export = new OrdersTransferredExport(
            OrderProductPivot::whereBetween('shipped_at', [$args['date_from'], $args['date_to']])
            ->with('order', 'order.shippingAddress', 'product')
            ->orderBy('shipped_at')
            ->get()
        );

        $fileName = 'orders_transferred_'.$args['date_from']->format('Y-m-d').'_'.$args['date_to']->format('Y-m-d').'.xlsx';

        Excel::store($export, $fileName);

        $path = 'public/exports/'.$fileName;

        if (Storage::exists($path)) {
            Storage::delete($path);
        }

        Storage::move($fileName, $path);

        return 'storage/exports/'.$fileName;
    }
}