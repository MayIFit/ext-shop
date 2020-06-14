<?php

namespace App\GraphQL\Queries\Core;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Models\User;

class AllRetailUser
{
    /**
     * List all reseller users
     * 
     * @return void
     */
    public static function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) {
        return User::where('reseller')->get();
    }
}