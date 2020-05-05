<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobStatusResource extends JsonResource
{
    private $modelResource = null;

    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'job';

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, $modelResource = null)
    {
        parent::__construct($resource);

        $this->modelResource = $modelResource;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'queue' => $this->queue,
            'attempts' => $this->attempts,
            'status' => $this->status,
            'output' => $this->output,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'data' => $this->modelResource,
            'links' => [
                'status' => url('/api/v1/job/' . $this->id),
            ],
        ];
    }
}
