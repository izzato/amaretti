<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateProjectsTest extends TestCase
{
    use DatabaseTransactions;

    protected $project;

    public function setUp()
    {
        parent::setUp();

        $this->project = create('App\Project');
    }

    /** @test */
    public function guests_may_not_update_a_project()
    {
        $this->withExceptionHandling();

        $this->get(route('projects.edit', $this->project))
            ->assertRedirect('login');

        $this->put(route('projects.update', $this->project))
            ->assertRedirect('login');

        $this->patch(route('projects.update', $this->project))
            ->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_cannot_update_a_project_that_is_not_theirs()
    {
        $this->withExceptionHandling();

        $this->project->title = 'Updated';

        $this->put(route('projects.update', $this->project), $this->project->toArray())
            ->assertRedirect('login');

        $this->patch(route('projects.update', $this->project), $this->project->toArray())
            ->assertRedirect('login');

        $this->assertDatabaseMissing('projects', $this->project->toArray());
    }

    /** @test */
    public function an_authenticated_user_can_update_their_own_project()
    {
        $user = create('App\User');

        $this->signIn($user, 'owner');

        $this->project = create('App\Project', ['user_id' => $user->id]);

        $this->project->title = 'Updated';

        $this->put(route('projects.update', $this->project), $this->project->toArray())
            ->assertStatus(302);

        $this->get(route('projects.show', $this->project))
            ->assertSee($this->project->title);

        $this->assertDatabaseHas('projects', $this->project->toArray());

        $this->patch(route('projects.update', $this->project), $this->project->toArray())
            ->assertStatus(302);

        $this->get(route('projects.show', $this->project))
            ->assertSee($this->project->title);

        $this->assertDatabaseHas('projects', $this->project->toArray());
    }

    /** @test */
    public function an_admin_user_can_update_any_project()
    {
        $user = create('App\User');

        $this->signIn($user, 'admin');

        $this->project->title = 'Updated';

        $this->put(route('projects.update', $this->project), $this->project->toArray())
            ->assertStatus(302);

        $this->get(route('projects.show', $this->project))
            ->assertSee($this->project->title);

        $this->assertDatabaseHas('projects', $this->project->toArray());

        $this->patch(route('projects.update', $this->project), $this->project->toArray())
            ->assertStatus(302);

        $this->get(route('projects.show', $this->project))
            ->assertSee($this->project->title);

        $this->assertDatabaseHas('projects', $this->project->toArray());
    }
}
