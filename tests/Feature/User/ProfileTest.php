<?php

namespace Tests\Feature\User;

use Tests\Feature\FeatureCase;

class ProfileTest extends FeatureCase
{
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
