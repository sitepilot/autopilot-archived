<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\ServerGroup;
use Laravel\Sanctum\Sanctum;

class ServerHostTest extends TestCase
{
    protected $endpoint = "/api/v1/hosts/";

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
     * Test user can create a host.
     *
     * @return void
     */
    public function test_user_can_create_a_host()
    {
        $group = $this->getLastResource($this->groupsEndpoint);

        $data = [
            'group_id' => $group->id,
            'description' => 'Test server.'
        ];

        $config = [
            'ansible_ssh_host' => 'autopilot-test'
        ];

        $response = $this->json('POST', $this->endpoint, array_merge($data, ["config" => $config]));

        $response->assertCreated();
        $response->assertJsonFragment($data);
        $response->assertJsonFragment($config);
    }

    /**
     * Test user can list hosts.
     *
     * @return void
     */
    public function test_user_can_list_hosts()
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
                        'group_id',
                        'client_id',
                        'state',
                        'config' => [],
                        'public_key',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]
        );
    }

    /**
     * Test user can update a host.
     *
     * @return void
     */
    public function test_user_can_update_a_host()
    {
        $host = $this->getLastResource();

        $data = [
            'description' => "Updated description."
        ];

        $config = [
            "admin_pass" => "supersecret123"
        ];

        $response = $this->json('PATCH', $this->endpoint . $host->id, array_merge($data, ["config" => $config]));
        $response->assertStatus(200);
        $response->assertJsonFragment($data);
        $response->assertJsonFragment($config);
    }

    /**
     * Test user can provision a host.
     *
     * @return void
     */
    public function test_user_can_provision_a_host()
    {
        $host = $this->getFirstResource();

        $response = $this->json('POST', $this->endpoint . $host->id . '/provision');

        $this->waitForJob($response);
    }

    /**
     * Test user can test a host.
     *
     * @return void
     */
    public function test_user_can_test_a_host()
    {
        $host = $this->getFirstResource();

        $response = $this->json('POST', $this->endpoint . $host->id . '/test');

        $this->waitForJob($response);
    }

    /**
     * Test user can renew certificates.
     *
     * @return void
     */
    public function test_user_can_renew_certs()
    {
        $host = $this->getFirstResource();

        $response = $this->json('POST', $this->endpoint . $host->id . '/cert-renew');

        $this->waitForJob($response);
    }
}
