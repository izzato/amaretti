<?php

namespace Tests\Feature;

use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewProjectsTest extends TestCase
{
    use DatabaseTransactions;

    protected $project;

    public function setUp()
    {
        parent::setUp();

        $this->unpublishedProject = create('App\Project');

        $this->publishedProject = create('App\Project', ['published_at' => now()]);
    }

    /** @test */
    public function guests_can_view_all_projects()
    {
        $this->get(route('projects.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function guests_can_view_a_published_project()
    {
        $this->withExceptionHandling();

        $this->get(route('projects.show', $this->publishedProject))
            ->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_an_unpublished_project()
    {
        $this->withExceptionHandling();

        $this->get(route('projects.show', $this->unpublishedProject))
            ->assertStatus(404);
    }

    /** @test */
    public function authenticated_users_can_view_all_projects()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $this->get(route('projects.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function authenticated_users_can_view_a_published_project()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $this->get(route('projects.show', $this->publishedProject))
            ->assertStatus(200);
    }

    /** @test */
    public function authenticated_users_cannot_view_an_unpublished_project()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $this->get(route('projects.show', $this->unpublishedProject))
            ->assertStatus(404);
    }

    /** @test */
    public function users_with_correct_permissions_can_view_a_published_project()
    {
        $this->withExceptionHandling();

        foreach(Role::all() as $role) {
            $this->signIn(null, $role->name);

            $this->get(route('projects.show', $this->publishedProject))
                ->assertStatus(200);
        }
    }

    /** @test */
    public function users_with_correct_permissions_can_view_an_unpublished_project()
    {
        $this->withExceptionHandling();

        foreach (Role::all() as $role) {
            $this->signIn(null, $role->name);

            $this->get(route('projects.show', $this->unpublishedProject))
                ->assertStatus(200);
        }
    }
}
