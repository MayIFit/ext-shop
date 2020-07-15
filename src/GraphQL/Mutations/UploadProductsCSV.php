<?php

namespace MayIFit\Extension\Shop\GraphQL\Mutations;

use Illuminate\Support\Facades\Storage;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Maatwebsite\Excel\Facades\Excel;

use MayIFit\Core\Permission\Models\Document;
use MayIFit\Extension\Shop\Imports\ProductsImport;

class UploadProductsCSV
{
    /**
     * Parse and process the uploaded CSV file
     * 
     * @return void
     */
    public function parseUploadedCSV($file) {
        Excel::import(new ProductsImport, $file);

    }

    /**
     * Upload a file, store it on the server and return the path.
     *
     * @param  mixed  $root
     * @param  mixed[]  $args
     * @return array|null
     */
    public function resolve($root, array $args): ?array
    {
        
        /** @var \Illuminate\Http\UploadedFile $file */
        $files = $args['input'];
        
        $retFiles = [];
        
        foreach ($files as $element) {
            $type = $element['type'];
            
            /** @var \Illuminate\Http\UploadedFile $file */
            $file = $element['file'];
            $storedPath = $file->storeAs('private/uploads', $file->getClientOriginalName());
            $pathArray = explode('/', $storedPath);
            $name = array_pop($pathArray);

            $document = new Document();
            $document->name = $name;
            $document->type = $file->getMimeType();
            $document->size = $file->getSize();
            $document->resource_url = rtrim(config('app.url'), '/').Storage::url($storedPath);
            $document->original_filename = $file->getClientOriginalName();
            $document->save();

            $this->parseUploadedCSV($storedPath);

            $retFiles[] = [
                'original_filename' => $file->getClientOriginalName(),
                'name' => $document->name,
                'resource_url' => $document->resource_url,
                'size' => $document->size,
                'type' => $document->type,
                'id' => $document->id
            ];
        }

        return $retFiles;
    }
}