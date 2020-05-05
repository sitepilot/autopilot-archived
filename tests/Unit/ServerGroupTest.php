<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class ServerGroupTest extends TestCase
{
    protected $endpoint = "/api/v1/groups/";

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
     * Test user can create a group.
     *
     * @return void
     */
    public function test_user_can_create_a_group()
    {
        $data = [
            'name' => 'test-web',
            'description' => 'Test group.'
        ];

        $response = $this->json('POST', $this->endpoint, $data);

        $response->assertCreated();
        $response->assertJsonFragment($data);
    }

    /**
     * Test user can list groups.
     *
     * @return void
     */
    public function test_user_can_list_groups()
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
                        'config' => [],
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]
        );
    }

    /**
     * Test user can update a group.
     *
     * @return void
     */
    public function test_user_can_update_a_group()
    {
        $group = $this->getLastResource();

        $data = [
            "description" => "Group description update.",
        ];

        $config = [
            "php_post_max_size" => "128M"
        ];

        $response = $this->json('PATCH', $this->endpoint . $group->id, array_merge($data, ["config" => $config]));

        $response->assertStatus(200);
        $response->assertJsonFragment($data);
        $response->assertJsonFragment($config);
    }
}
