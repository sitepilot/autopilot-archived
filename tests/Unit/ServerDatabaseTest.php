<?php

namespace Tests\Unit;

use App\User;
use App\ServerUser;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class ServerDatabaseTest extends TestCase
{
    protected $endpoint = "/api/v1/databases/";

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
     * Test user can create a database.
     *
     * @return void
     */
    public function test_user_can_create_a_database()
    {
        $user = $this->getLastResource($this->usersEndpoint);

        $data = [
            'user_id' => $user->id,
            'description' => 'Test database.'
        ];

        $config = [
            //
        ];

        $response = $this->json('POST', $this->endpoint, array_merge($data, ["config" => $config]));

        $response->assertCreated();
        $response->assertJsonFragment($data);
        $response->assertJsonFragment($config);

        $this->waitForJob($response);
    }

    /**
     * Test user can list databases.
     *
     * @return void
     */
    public function test_user_can_list_databases()
    {
        $response = $this->json('GET', $this->endpoint);
        $response->assertStatus(200);

        $response->assertJsonStructure(
            [
                "data" =>
                [
                    [
                        'id',
                        'name',
                        'description',
                        'app_id',
                        'user_id',
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
     * Test user can update a database.
     *
     * @return void
     */
    public function test_user_can_update_a_database()
    {
        $database = $this->getLastResource();

        $data = [
            'description' => "Updated description."
        ];

        $config = [
            //
        ];

        $response = $this->json('PATCH', $this->endpoint . $database->id, array_merge($data, ["config" => $config]));

        $response->assertStatus(200);
        $response->assertJsonFragment($data);
        $response->assertJsonFragment($config);

        $this->waitForJob($response);
    }
}
