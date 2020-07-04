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
        'register' => base_path('graphql/extensions/'),
    ],
    'queries' => [
        'register' => base_path('app/GraphQL/Queries')
    ]
];
