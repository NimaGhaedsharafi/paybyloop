<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\Feature\FeatureCase;

class ProfileTest extends FeatureCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;
    /**
     * @test
     */
    public function get_user_profile_works()
    {
        $this->impersonate();

        $this->json('GET', route('v1.user.profile.show'))
            ->assertOk()
            ->assertJsonStructure([
                'first_name',
                'last_name',
                'cellphone',
                'email',
                'username',
            ]);
    }

    /**
     * @test
     */
    public function get_config_needs_build_number_and_os_then_return_update_info()
    {
        $data = [
            'build' => 100,
            'os' => 1 // for now it's android
        ];

        $this->json('POST', route('v1.user.config'), $data)
            ->assertOk()
            ->assertJsonStructure([
                'update_url',
                'latest_build',
                'supported_build',
            ]);
    }
}
