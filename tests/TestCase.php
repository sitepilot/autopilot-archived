<?php

namespace Tests;

use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Returns the first resource from the API.
     * 
     * @return object $resource
     */
    protected function getFirstResource()
    {
        $response = $this->json('GET', $this->endpoint);
        $response->assertStatus(200);

        return $response->getData()->data[0];
    }

    /**
     * Returns the last resource from the API.
     * 
     * @return object $resource
     */
    protected function getLastResource()
    {
        $response = $this->json('GET', $this->endpoint);
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

        $response = $this->json('GET', '/api/v1/jobs/' . $response->getData()->job->id);
        $response->assertStatus(200);

        if ($response->getData()->job->status == 'queued' || $response->getData()->job->status == 'executing') {
            sleep(2);
            $this->waitForJob($response);
        } else {
            $response->assertJsonFragment(["status" => "finished"]);
        }
    }
}
