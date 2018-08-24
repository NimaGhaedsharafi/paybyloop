<?php

namespace Tests\Feature;

use App\Vendor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class VendorTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    /**
     * @test
     */
    public function get_list_of_vendors_works()
    {
        factory(Vendor::class, 5)->create();

        $this->json('GET', route('v1.user.vendor.list'))
            ->assertOk()
            ->assertJson(Vendor::all()->toArray());
    }
}
