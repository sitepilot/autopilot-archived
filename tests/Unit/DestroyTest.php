<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class DestroyTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(
            User::first(),
            ['*']
        );
    }

    /**
     * Test user can destroy an app.
     *
     * @return void
     */
    public function test_user_can_destroy_an_app()
    {
        $app = $this->getLastResource($this->appsEndpoint);

        $this->response = $this->json('DELETE', $this->appsEndpoint . $app->id);

        $this->waitForJob($this->response);
    }

    /**
     * Test user can destroy a database.
     *
     * @return void
     */
    public function test_user_can_destroy_a_database()
    {
        $database = $this->getLastResource($this->databasesEndpoint);

        $this->response = $this->json('DELETE', $this->databasesEndpoint . $database->id);

        $this->waitForJob($this->response);
    }

    /**
     * Test user can destroy a user.
     *
     * @return void
     */
    public function test_user_can_destroy_a_user()
    {
        $user = $this->getLastResource($this->usersEndpoint);

        $this->response = $this->json('DELETE', $this->usersEndpoint . $user->id);

        $this->waitForJob($this->response);
    }

    /**
     * Test user can destroy a host.
     *
     * @return void
     */
    public function test_user_can_destroy_a_host()
    {
        $host = $this->getLastResource($this->hostsEndpoint);

        $this->response = $this->json('DELETE', $this->hostsEndpoint . $host->id);

        $this->response->assertStatus(204);
    }

    /**
     * Test user can destroy a group.
     *
     * @return void
     */
    public function test_user_can_destroy_a_group()
    {
        $host = $this->getLastResource($this->groupsEndpoint);

        $this->response = $this->json('DELETE', $this->groupsEndpoint . $host->id);

        $this->response->assertStatus(204);
    }
}
