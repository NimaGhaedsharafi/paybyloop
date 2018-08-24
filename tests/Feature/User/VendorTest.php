<?php

namespace Tests\Feature\User;

use App\Vendor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\Feature\FeatureCase;

/**
 * Class VendorTest
 * @package Tests\Feature\User
 */
class VendorTest extends FeatureCase
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

    /**
     * @test
     */
    public function get_vendor_info()
    {
        $vendor = factory(Vendor::class)->create();

        $this->json('GET', route('v1.user.vendor.show', ['vendor_id' => $vendor->vendor_id]))
            ->assertOk()
            ->assertJsonStructure([
                'name',
                'photo',
                'address',
                'coordinate',
                'vendor_id',
            ]);
    }
}
