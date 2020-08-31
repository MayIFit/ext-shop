<?php

namespace MayIFit\Extension\Shop\GraphQL\Queries;

use Carbon\Carbon;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Maatwebsite\Excel\Facades\Excel;
use MayIFit\Extension\Shop\Exports\StockMovementsExport;

/**
 * Class GetStockMovements
 *
 * @package MayIFit\Extension\Shop
 */
class GetStockMovements
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $export = new StockMovementsExport(
            Collection::make(DB::table('stock_movements')
                ->join('products', 'products.id', '=', 'stock_movements.product_id')
                ->leftJoin('orders', 'orders.id', '=', 'stock_movements.order_id')
                ->select('products.catalog_id', 'original_quantity', 'incoming_quantity', 'difference', 'source', 'stock_movements.calculated_stock', 'orders.order_id_prefix', 'stock_movements.created_at')
                ->whereBetween('stock_movements.created_at', [$args['datetime_from'], $args['datetime_to']])
                ->get())
        );

        $fileName = 'stock_movement' . $args['datetime_from']->format('Y-m-d') . '_' . $args['datetime_to']->format('Y-m-d') . '.xlsx';

        Excel::store($export, $fileName);

        $path = 'public/exports/' . $fileName;

        if (Storage::exists($path)) {
            Storage::delete($path);
        }

        Storage::move($fileName, $path);

        return 'storage/exports/' . $fileName;
    }
}
