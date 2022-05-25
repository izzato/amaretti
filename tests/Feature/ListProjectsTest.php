<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ListProjectsTest extends TestCase
{
    use DatabaseTransactions;

    protected $project;

    public function setUp()
    {
        parent::setUp();

        $this->project = create('App\Project');
    }

    /** @test */
    public function guests_cannot_list_projects()
    {
        $this->withExceptionHandling();

        $this->get(route('projects.list'))
            ->assertRedirect('login');;
    }

    /** @test */
    public function users_without_the_correct_permissions_cannot_list_projects()
    {
        $this->expectException('Spatie\Permission\Exceptions\UnauthorizedException');

        $this->signIn();

        $this->get(route('projects.list'));
    }

    /** @test */
    public function only_users_with_correct_permissions_can_list_projects()
    {
        $this->withExceptionHandling();

        $this->signIn(null, 'owner');

        $this->get(route('projects.list'))
            ->assertStatus(200);

        $this->signIn(null, 'admin');

        $this->get(route('projects.list'))
            ->assertStatus(200);
    }
}
