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
        $this->json('GET', route('v1.user.profile.show'))
            ->assertOk()
            ->assertJsonFragment([
                'name',
                'cellphone',
                'email',
                'username',
            ]);
    }
}
