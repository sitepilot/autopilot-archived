<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Imtigger\LaravelJobStatus\JobStatus;
use App\Http\Resources\JobStatusResource;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Update config of a model.
     *
     * @param Request $request
     * @param Model $model
     * @return Model
     */
    public function updateConfig(Request $request, Model $model)
    {
        $defaultVars = $model->getDefaultVars();

        // Disable name and hostname update
        unset($defaultVars['name']);
        unset($defaultVars['hostname']);

        foreach ($request->input('config', []) as $key => $value) {
            if (Arr::has($defaultVars, $key)) {
                $model->setVar($key, $value);
            }
        }

        return $model;
    }

    /**
     * Dispatches a job by name.
     * 
     * @param string $class
     * @param Request $request
     * @param Model $host
     * @return Response
     */
    public function dispatchJob($class, $resourceClass, Request $request, Model $model, $status = 200)
    {
        $job = new $class($model, $request);
        $this->dispatch($job);

        if ($request->input('dispatch') == 'now') {
            while (JobStatus::find($job->getJobStatusId())->finished_at == null) {
                sleep(1);
            }
        }

        $jobStatus = JobStatus::find($job->getJobStatusId());
        $statusCode = in_array($jobStatus->status, ['retrying', 'failed']) ? 500 : $status;

        return (new JobStatusResource(
            $jobStatus,
            new $resourceClass($model),
        ))
            ->response()
            ->setStatusCode($statusCode);
    }
}
