<?php

namespace MayIFit\Extension\Shop\Tests\Feature;

use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;

use App\Models\User;

class CanUpdateResellerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_reseller(): void
    {
        parent::setUp();

        $user = new User;
        $user->name = 'John Doe';
        $user->email = 'john@doe.test';
        $user->password = '123456789';
        $user->save();
        Sanctum::actingAs($user, ['*']);

        $this->graphQL('
            mutation {
                createReseller(input: {
                    user: {
                        connect: ' . $user->id . '
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
        ');

        $this->graphQL('
            mutation {
                updateReseller(input: {
                    id: 1
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
