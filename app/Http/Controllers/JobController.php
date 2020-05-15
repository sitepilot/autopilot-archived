<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobStatusResource;
use App\ServerHost;
use Illuminate\Http\Response;
use Imtigger\LaravelJobStatus\JobStatus;

class JobController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param ServerHost $host
     * @return Response
     */
    public function show(JobStatus $job)
    {
        $statusCode = in_array($job->status, ['retrying', 'failed']) ? 500 : 200;

        return (new JobStatusResource($job, null))
            ->response()
            ->setStatusCode($statusCode);
    }
}
