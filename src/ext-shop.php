<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Schema Location
    |--------------------------------------------------------------------------
    |
    | Path to your .graphql schema file.
    | Additional schema files may be imported from within that file.
    |
    */

    'schema' => [
        'register' => base_path('graphql/extensions'),
    ],
    'queries' => [
        'register' => base_path('app/GraphQL/Queries')
    ],
    'mutations' => [
        'register' => base_path('app/GraphQL/Mutations')
    ],
    'courier_api_endpoint' => env('COURIER_API_ENDPOINT', 'http://10.2.9.60/WMSImportService/Orders.svc?wsdl'),
];
