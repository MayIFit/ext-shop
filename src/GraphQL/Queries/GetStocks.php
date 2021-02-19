<?php

namespace MayIFit\Extension\Shop\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Storage;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Maatwebsite\Excel\Facades\Excel;

use MayIFit\Extension\Shop\Exports\StockExport;
use MayIFit\Extension\Shop\Models\Product;

/**
 * Class GetStock
 *
 * @package MayIFit\Extension\Shop
 */
class GetStocks
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $export = new StockExport(Product::orderByRaw('catalog_id * 1 asc, catalog_id asc')->get());

        $fileName = 'stocks_' . date("Y-m-d") . '.xlsx';

        Excel::store($export, $fileName);

        $path = 'public/exports/' . $fileName;

        if (Storage::exists($path)) {
            Storage::delete($path);
        }

        Storage::move($fileName, $path);

        return 'storage/exports/' . $fileName;
    }
}
