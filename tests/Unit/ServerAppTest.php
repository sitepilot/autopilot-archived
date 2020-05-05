<?php

namespace Tests\Unit;

use App\User;
use App\ServerUser;
use Tests\TestCase;
use App\ServerDatabase;
use Laravel\Sanctum\Sanctum;

class ServerAppTest extends TestCase
{
    protected $endpoint = "/api/v1/apps/";

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
     * Test user can create an app.
     *
     * @return void
     */
    public function test_user_can_create_an_app()
    {
        $user = $this->getLastResource($this->usersEndpoint);

        $data = [
            'user_id' => $user->id,
            'description' => 'Test app.'
        ];

        $config = [
            'aliases' => ['test-domain.com']
        ];

        $this->response = $this->json('POST', $this->endpoint, array_merge($data, ["config" => $config]));

        $this->response->assertCreated();
        $this->response->assertJsonFragment($data);
        $this->response->assertJsonFragment($config);

        $this->waitForJob($this->response);
    }

    /**
     * Test user can list apps.
     *
     * @return void
     */
    public function test_user_can_list_apps()
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
     * Test user can update an app.
     *
     * @return void
     */
    public function test_user_can_update_an_app()
    {
        $app = $this->getLastResource();

        $data = [
            'description' => "Updated description."
        ];

        $config = [
            "aliases" => ["test-domain2.com"]
        ];

        $this->response = $this->json('PATCH', $this->endpoint . $app->id, array_merge($data, ["config" => $config]));

        $this->response->assertStatus(200);
        $this->response->assertJsonFragment($data);
        $this->response->assertJsonFragment($config);

        $this->waitForJob($this->response);
    }

    /**
     * Test user can install WordPress.
     *
     * @return void
     */
    public function test_user_can_install_wordpress()
    {
        $app = $this->getLastResource();
        $database = $this->getLastResource($this->databasesEndpoint);

        $data = [
            'database_id' => $database->id,
            'admin_user' => 'captain',
            'admin_pass' => 'supersecret',
            'admin_email' => 'website@sitepilot.io'
        ];

        $this->response = $this->json('POST', $this->endpoint . $app->id . '/wp/install', $data);

        $this->waitForJob($this->response);
    }

    /**
     * Test user can check WordPress state.
     *
     * @return void
     */
    public function test_user_can_check_wordpress_state()
    {
        $app = $this->getLastResource();

        $this->response = $this->json('POST', $this->endpoint . $app->id . '/wp/check-state');

        $this->waitForJob($this->response);
    }

    /**
     * Test user can generate WordPress login link.
     *
     * @return void
     */
    public function test_user_can_generate_wordpress_login_link()
    {
        $app = $this->getLastResource();

        $this->response = $this->json('POST', $this->endpoint . $app->id . '/wp/login');

        $this->waitForJob($this->response);
    }

    /**
     * Test user can search and replace in WordPress database.
     *
     * @return void
     */
    public function test_user_can_search_replace_wordpress_database()
    {
        $app = $this->getLastResource();

        $this->response = $this->json('POST', $this->endpoint . $app->id . '/wp/search-replace', [
            "search" => "random-test-string-" . time(),
            "replace" => "random-test-string-" . (time() + 1000),
        ]);

        $this->waitForJob($this->response);
    }

    /**
     * Test user can update WordPress.
     *
     * @return void
     */
    public function test_user_can_update_wordpress()
    {
        $app = $this->getLastResource();

        $this->response = $this->json('POST', $this->endpoint . $app->id . '/wp/update');

        $this->waitForJob($this->response);
    }
}
