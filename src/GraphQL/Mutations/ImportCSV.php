<?php

namespace MayIFit\Extension\Shop\GraphQL\Mutations;

use Illuminate\Support\Facades\Storage;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Maatwebsite\Excel\Facades\Excel;

use MayIFit\Extension\Shop\Imports\ProductsImport;
use MayIFit\Extension\Shop\Imports\ProductPricingsImport;

class ImportCSV
{
    /**
     * Parse a file on the given path and import it
     *
     * @param  mixed  $root
     * @param  mixed[]  $args
     * @return array|null
     */
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): void {
        $files = $args['input'];

        foreach ($files as $file) {
            if ($file['type'] === 'product') {
                Excel::import(new ProductsImport, $file['path']);
            } else if ($file['type'] === 'product-pricing') {
                Excel::import(new ProductPricingsImport, $file['path']);
            }
        }
    }
}