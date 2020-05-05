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
        return new JobStatusResource($job, null);
    }
}
