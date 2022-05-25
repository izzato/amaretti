<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    protected function signIn($user = null, $role = null)
    {
        $user = $user ?: create('App\User');

        (!$role) ?: $user->assignRole($role);

        $this->be($user);

        return $this;
    }
}
