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
                'name',
                'cellphone',
                'email',
                'username',
            ]);
    }
}
