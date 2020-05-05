<?php

namespace App\Http\Controllers;

use App\ServerHost;
use App\Jobs\ServerTestJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Jobs\ServerCertRenewJob;
use App\Jobs\ServerProvisionJob;
use App\Http\Requests\ServerHostRequest;
use App\Http\Resources\ServerHostResource;

class ServerHostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return ServerHostResource::collection(ServerHost::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ServerHostRequest $request
     * @return Response
     */
    public function store(ServerHostRequest $request)
    {
        $host = ServerHost::create($request->only([
            'group_id',
            'client_id',
            'description'
        ]));

        $this->updateConfig($request, $host)->save();

        return new ServerHostResource($host);
    }

    /**
     * Display the specified resource.
     *
     * @param ServerHost $host
     * @return Response
     */
    public function show(ServerHost $host)
    {
        return new ServerHostResource($host);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ServerHostRequest $request
     * @param ServerHost $host
     * @return Response
     */
    public function update(ServerHostRequest $request, ServerHost $host)
    {
        $host->update($request->only([
            'description'
        ]));

        $this->updateConfig($request, $host)->save();

        return new ServerHostResource($host);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ServerHost $host
     * @return Response
     */
    public function destroy(ServerHost $host)
    {
        $host->delete();

        return response()->json(null, 204);
    }

    /**
     * Provision the specified resource.
     * 
     * @param Request $request
     * @param ServerHost $host
     * @return Response
     */
    public function provision(Request $request, ServerHost $host)
    {
        return $this->dispatchJob(ServerProvisionJob::class, ServerHostResource::class, $request, $host);
    }

    /**
     * Test the specified resource.
     * 
     * @param Request $request
     * @param ServerHost $host
     * @return Response
     */
    public function test(Request $request, ServerHost $host)
    {
        return $this->dispatchJob(ServerTestJob::class, ServerHostResource::class, $request, $host);
    }

    /**
     * Renew certificates on the specified resource.
     * 
     * @param Request $request
     * @param ServerHost $host
     * @return Response
     */
    public function certRenew(Request $request, ServerHost $host)
    {
        return $this->dispatchJob(ServerCertRenewJob::class, ServerHostResource::class, $request, $host);
    }
}
