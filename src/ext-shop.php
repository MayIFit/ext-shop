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
    'courier_api_endpoint' => env('COURIER_API_ENDPOINT', ''),
    'courier_api_username' => env('COURIER_API_USERNAME', ''),
    'courier_api_password' => env('COURIER_API_PASSWORD', ''),
    'courier_api_userid' => env('COURIER_API_USERID', ''),
    'courier_api_userid' => env('COURIER_API_USERID', ''),
    'courier_api_test' => env('COURIER_API_TEST', TRUE),
    'warehouse_emails' => env('WAREHOUSE_EMAILS', '')
];
