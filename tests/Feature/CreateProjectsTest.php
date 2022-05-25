<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateProjectsTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function guests_may_not_create_a_project()
    {
        $this->withExceptionHandling();

        $this->get(route('projects.create'))
            ->assertRedirect('login');

        $project = make('App\Project', ['published_at' => now()]);

        $this->post(route('projects.store'), $project->toArray())
            ->assertRedirect('login');

        $this->assertDatabaseMissing('projects', $project->toArray());
    }

    /** @test */
    public function an_authenticated_user_can_see_the_project_creation_page()
    {
        $this->withExceptionHandling();

        $this->signIn(null, 'owner');

        $this->get(route('projects.create'))
            ->assertStatus(200);
    }

    /** @test */
    public function an_authenticated_user_cannot_create_a_project_without_correct_permissions()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $project = make('App\Project', ['published_at' => now()]);

        $this->post(route('projects.store'), $project->toArray())
            ->assertStatus(403);

        $this->assertDatabaseMissing('projects', $project->toArray());
    }

    /** @test */
    public function an_authenticated_user_can_create_a_project()
    {
        $this->withExceptionHandling();

        $this->signIn(null, 'owner');

        $project = make('App\Project', ['published_at' => now()]);

        $this->post(route('projects.store'), $project->toArray())
            ->assertStatus(302);

        $latest = Project::latest()->first();

        $this->get(route('projects.show', $latest))
            ->assertStatus(200)
            ->assertSee($project->title);

        $this->assertDatabaseHas('projects', $project->toArray());
    }

    /** @test */
    public function an_admin_user_can_create_a_project()
    {
        $this->withExceptionHandling();

        $this->signIn(null, 'admin');

        $project = make('App\Project', ['published_at' => now()]);

        $this->post(route('projects.store'), $project->toArray())
            ->assertStatus(302);

        $latest = Project::latest()->first();

        $this->get(route('projects.show', $latest))
            ->assertStatus(200)
            ->assertSee($project->title);

        $this->assertDatabaseHas('projects', $project->toArray());
    }
}
