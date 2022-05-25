<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BasicPageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function unauthenticated_users_may_not_see_the_dashboard()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->get(route('home'));
    }

    /** @test */
    public function a_user_can_view_the_sitemap()
    {
        $this->get('/sitemap.xml')
            ->assertStatus(200);
    }

    /** @test */
    public function a_user_can_view_the_home_page()
    {
        $this->get('/')
            ->assertStatus(200);
    }
}
