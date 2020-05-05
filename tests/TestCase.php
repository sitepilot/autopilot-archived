<?php

namespace Tests;

use Throwable;
use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected TestResponse $response;
    protected $appsEndpoint = "/api/v1/apps/";
    protected $hostsEndpoint = "/api/v1/hosts/";
    protected $usersEndpoint = "/api/v1/users/";
    protected $groupsEndpoint = "/api/v1/groups/";
    protected $databasesEndpoint = "/api/v1/databases/";

    /**
     * Returns the first resource from the API.
     * 
     * @param string $endpoint
     * @return object $resource
     */
    protected function getFirstResource($endpoint = null)
    {
        if (!$endpoint) {
            $endpoint = $this->endpoint;
        }

        $response = $this->json('GET', $endpoint);
        $response->assertStatus(200);

        return $response->getData()->data[0];
    }

    /**
     * Returns the last resource from the API.
     * 
     * @param string $endpoint
     * @return object $resource
     */
    protected function getLastResource($endpoint = null)
    {
        if (!$endpoint) {
            $endpoint = $this->endpoint;
        }

        $response = $this->json('GET', $endpoint);
        $response->assertStatus(200);

        return end($response->getData()->data);
    }

    /**
     * Wait for a specific job.
     *
     * @param TestResponse $response
     * @return void
     */
    protected function waitForJob(TestResponse $response)
    {
        $response->assertJsonStructure(["job" => ["id"]]);

        $this->response = $this->json('GET', '/api/v1/jobs/' . $response->getData()->job->id);
        $this->response->assertStatus(200);

        if ($this->response->getData()->job->status == 'queued' || $this->response->getData()->job->status == 'executing') {
            sleep(2);
            $this->waitForJob($response);
        } else {
            $this->response->assertJsonFragment(["status" => "finished"]);
        }
    }

    /**
     * This method is called when a test method did not execute successfully.
     *
     * @throws Throwable
     */
    protected function onNotSuccessfulTest(Throwable $t): void
    {
        if ($this->response) {
            fwrite(STDERR, print_r($this->response->getData(), true));           
        }

        parent::onNotSuccessfulTest($t);
    }
}
