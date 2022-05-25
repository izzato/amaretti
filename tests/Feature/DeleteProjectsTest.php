<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteProjectsTest extends TestCase
{
    use DatabaseTransactions;

    protected $project;

    public function setUp()
    {
        parent::setUp();

        $this->project = create('App\Project');
    }

    /** @test */
    public function guests_may_not_trash_a_project()
    {
        $this->withExceptionHandling();

        $this->patch(route('projects.trash', $this->project))
            ->assertRedirect('login');

        $this->assertDatabaseHas('projects', $this->project->toArray());
    }

    /** @test */
    public function guests_may_not_restore_a_project()
    {
        $this->withExceptionHandling();

        $this->patch(route('projects.restore', $this->project))
            ->assertRedirect('login');

        $this->assertDatabaseHas('projects', $this->project->toArray());
    }

    /** @test */
    public function guests_may_not_delete_a_project()
    {
        $this->withExceptionHandling();

        $this->delete(route('projects.destroy', $this->project))
            ->assertRedirect('login');

        $this->assertDatabaseHas('projects', $this->project->toArray());
    }

    /** @test */
    public function an_authenticated_user_cannot_trash_a_project_without_correct_permissions()
    {
        $this->withExceptionHandling();

        $this->patch(route('projects.trash', $this->project))
            ->assertRedirect('login');

        $this->assertDatabaseHas('projects', $this->project->toArray());
    }

    /** @test */
    public function an_authenticated_user_cannot_restore_a_project_without_correct_permissions()
    {
        $this->withExceptionHandling();

        $this->patch(route('projects.restore', $this->project))
            ->assertRedirect('login');

        $this->assertDatabaseHas('projects', $this->project->toArray());
    }

    /** @test */
    public function an_authenticated_user_cannot_delete_a_project_without_correct_permissions()
    {
        $this->withExceptionHandling();

        $this->delete(route('projects.destroy', $this->project))
            ->assertRedirect('login');

        $this->assertDatabaseHas('projects', $this->project->toArray());
    }

    /** @test */
    public function an_authenticated_user_can_trash_their_own_project()
    {
        $user = create('App\User');

        $this->signIn($user, 'owner');

        $this->project = create('App\Project', ['user_id' => $user->id]);

        $this->patch(route('projects.trash', $this->project))
            ->assertStatus(302);

        $this->assertDatabaseHas('projects', $this->project->toArray());
    }

    /** @test */
    public function an_authenticated_user_can_restore_their_own_trashed_project()
    {
        $user = create('App\User');

        $this->signIn($user, 'owner');

        $this->project = create('App\Project', ['user_id' => $user->id]);

        $this->patch(route('projects.trash', $this->project))
            ->assertStatus(302);

        $this->patch(route('projects.restore', $this->project))
            ->assertStatus(302);

        $this->assertDatabaseHas('projects', $this->project->toArray());
    }

    /** @test */
    public function an_authenticated_user_can_delete_their_own_trashed_project()
    {
        $user = create('App\User');

        $this->signIn($user, 'owner');

        $this->project = create('App\Project', ['user_id' => $user->id]);

        $this->patch(route('projects.trash', $this->project))
            ->assertStatus(302);

        $this->delete(route('projects.destroy', $this->project))
            ->assertStatus(302);

        $this->assertDatabaseMissing('projects', $this->project->toArray());
    }

    /** @test */
    public function an_admin_user_can_trash_any_non_trashed_project()
    {
        $user = create('App\User');

        $this->signIn($user, 'admin');

        $this->patch(route('projects.trash', $this->project))
            ->assertStatus(302);

        $this->assertDatabaseHas('projects', $this->project->toArray());
    }

    /** @test */
    public function an_admin_user_can_restore_any_trashed_project()
    {
        $user = create('App\User');

        $this->signIn($user, 'admin');

        $this->patch(route('projects.trash', $this->project))
            ->assertStatus(302);

        $this->patch(route('projects.restore', $this->project))
            ->assertStatus(302);

        $this->assertDatabaseHas('projects', $this->project->toArray());
    }

    /** @test */
    public function an_admin_user_can_delete_any_trashed_project()
    {
        $user = create('App\User');

        $this->signIn($user, 'admin');

        $this->patch(route('projects.trash', $this->project))
            ->assertStatus(302);

        $this->delete(route('projects.destroy', $this->project))
            ->assertStatus(302);

        $this->assertDatabaseMissing('projects', $this->project->toArray());
    }
}
