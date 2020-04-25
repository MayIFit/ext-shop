<?php

namespace App\GraphQL\Directives;

use Closure;
use Illuminate\Support\Str;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class OrderTokenDirective extends BaseDirective implements FieldMiddleware
{
    public function handleField(FieldValue $fieldValue, Closure $next): FieldValue
    {
        /// Wrap around the resolver
        $wrappedResolver = function ($root, array $args, GraphQLContext $context, ResolveInfo $info) : string {
            return Str::random(40);;
        };

        // Place the wrapped resolver back upon the FieldValue
        // It is not resolved right now - we just prepare it
        $fieldValue->setResolver($wrappedResolver);

        // Keep the middleware chain going
        return $next($fieldValue);
    }
}
