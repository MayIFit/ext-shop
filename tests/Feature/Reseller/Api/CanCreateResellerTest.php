<?php

namespace MayIFit\Extension\Shop\Tests\Feature\Api;

use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;
use MayIFit\Extension\Shop\Tests\User;

class CanCreateResellerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_reseller(): void
    {
        parent::setUp();

        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);

        $this->graphQL('
            mutation {
                createReseller(input: {
                    user: {
                        connect: {type: "user" id: ' . $user->id . '}
                    }
                    phone_number: "0123456789"
                    email: "test@test.com"
                    company_name: "Best company inc."
                    vat_id: "0123456789"
                    contact_person: "John Doe"
                }) {
                    vat_id
                    user {
                        id
                    }
                }
            }
        ')->assertJSON([
            'data' => [
                'createReseller' => [
                    'vat_id' => "0123456789",
                    'user' => [
                        'id' => $user->id
                    ]
                ]
            ]
        ]);
    }
}
