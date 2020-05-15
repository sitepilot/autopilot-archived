<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class ServerUserTest extends TestCase
{
    protected $endpoint = "/api/v1/users/";

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
     * Test user can create a user.
     *
     * @return void
     */
    public function test_user_can_create_a_user()
    {
        $host = $this->getFirstResource($this->hostsEndpoint);

        $data = [
            'host_id' => $host->id,
            'description' => 'Test user.'
        ];

        $config = [
            'email' => 'test@sitepilot.io'
        ];

        $this->response = $this->json('POST', $this->endpoint, array_merge($data, ["config" => $config]));

        $this->response->assertCreated();
        $this->response->assertJsonFragment($data);
        $this->response->assertJsonFragment($config);
    }

    /**
     * Test user can list users.
     *
     * @return void
     */
    public function test_user_can_list_users()
    {
        $this->response = $this->json('GET', $this->endpoint);
        $this->response->assertStatus(200);

        $this->response->assertJsonStructure(
            [
                "data" =>
                [
                    [
                        'id',
                        'name',
                        'description',
                        'host_id',
                        'client_id',
                        'state',
                        'config' => [],
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]
        );
    }

    /**
     * Test user can update a user.
     *
     * @return void
     */
    public function test_user_can_update_a_user()
    {
        $user = $this->getLastResource();

        $data = [
            'description' => "Updated description."
        ];

        $config = [
            "password" => "supersecret123"
        ];

        $this->response = $this->json('PATCH', $this->endpoint . $user->id, array_merge($data, ["config" => $config]));

        $this->response->assertStatus(200);
        $this->response->assertJsonFragment($data);
        $this->response->assertJsonFragment($config);
    }

    /**
     * Test user can test a user.
     *
     * @return void
     */
    public function test_user_can_test_a_user()
    {
        $user = $this->getLastResource();

        $this->response = $this->json('POST', $this->endpoint . $user->id . '/test');

        $this->waitForJob($this->response);
    }
}