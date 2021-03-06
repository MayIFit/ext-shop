<?php

namespace MayIFit\Extension\Shop\GraphQL\Mutations;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\JsonResponse;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Type\Definition\ResolveInfo;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

use MayIFit\Extension\Shop\Imports\ProductsImport;
use MayIFit\Extension\Shop\Imports\ProductPricingsImport;
use MayIFit\Extension\Shop\Imports\ResellersImport;
use MayIFit\Extension\Shop\Imports\UsersImport;

/**
 * Class ImportCSV
 *
 * @package MayIFit\Extension\Shop
 */
class ImportCSV
{
    /**
     * Parse a file on the given path and import it
     *
     * @param  mixed  $root
     * @param  mixed[]  $args
     * @return array|null
     */
    public function import($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): JsonResponse
    {
        $file = $args['input'];
        $import = [];

        if (!count($args['entity_mapping'])) {
            return Response::json('error.no_mapping_provided', 400);
        }

        $import[$file['path']] = '';
        if ($file['type'] === 'product') {
            $imp = new ProductsImport($args['entity_mapping']);
            Excel::import($imp, $file['path']);
            $import[$file['path']] = $imp->getImportedRowCount();
        } else if ($file['type'] === 'product-pricing') {
            $imp = new ProductPricingsImport($args['entity_mapping']);
            Excel::import($imp, $file['path']);
            $import[$file['path']] = $imp->getImportedRowCount();
        } else if ($file['type'] === 'reseller') {
            $imp = new ResellersImport($args['entity_mapping']);
            Excel::import($imp, $file['path']);
            $import[$file['path']] = $imp->getImportedRowCount();
        } else if ($file['type'] === 'user') {
            $imp = new UsersImport($args['entity_mapping']);
            Excel::import($imp, $file['path']);
            $import[$file['path']] = $imp->getImportedRowCount();
        }
        return Response::json($import[$file['path']], 200);
    }

    /**
     * Parse a file on the given path and import it
     *
     * @param  mixed  $root
     * @param  mixed[]  $args
     * @return array|null
     */
    public function getHeader($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): JsonResponse
    {
        $file = $args;
        $headings = (new HeadingRowImport)->toArray($file['path']);

        return Response::json($headings, 200);
    }
}
