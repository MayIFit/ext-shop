<?php

namespace MayIFit\Extension\Shop\GraphQL\Mutations;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Type\Definition\ResolveInfo;

use MayIFit\Extension\Shop\Models\Order;
use MayIFit\Extension\Shop\Jobs\SendOrderDataToWMS;

class SendOrderEvent
{
    /**
     * Dispatch and adhoc order sending job
     *
     * @param  mixed  $root
     * @param  mixed[]  $args
     * @return array|null
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) {
        if (!isset($args['id'])) {
            return false;
        }
        $order = Order::find($args['id']);
        dispatch(new SendOrderDataToWMS($order));
    }
}