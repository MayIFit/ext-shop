<?php

namespace MayIFit\Extension\Shop\Tests\Feature\Api;

use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;
use MayIFit\Extension\Shop\Tests\User;
use MayIFit\Extension\Shop\Models\Reseller;

class CanUpdateResellerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_reseller(): void
    {
        parent::setUp();

        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);

        $reseller = factory(Reseller::class)->create();

        $this->graphQL('
            mutation {
                updateReseller(input: {
                    id: ' . $reseller->id . '
                    phone_number: "9876543210"
                    email: "test@test.com"
                    company_name: "Best company inc."
                    vat_id: "0123456789"
                    contact_person: "John Doe"
                }) {
                    phone_number
                }
            }
        ')->assertJSON([
            'data' => [
                'updateReseller' => [
                    'phone_number' => '9876543210'
                ]
            ]
        ]);
    }
}
