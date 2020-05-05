<?php

namespace App\Http\Controllers;

use App\ServerDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Jobs\DatabaseDestroyJob;
use App\Jobs\DatabaseProvisionJob;
use App\Http\Requests\ServerDatabaseRequest;
use App\Http\Resources\ServerDatabaseResource;

class ServerDatabaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return ServerDatabaseResource::collection(ServerDatabase::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ServerDatabaseRequest $request
     * @return Response
     */
    public function store(ServerDatabaseRequest $request)
    {
        $database = ServerDatabase::create($request->only([
            'name',
            'app_id',
            'user_id',
            'description'
        ]));

        $database = $this->updateConfig($request, $database);
        $database->save();

        return $this->dispatchJob(DatabaseProvisionJob::class, ServerDatabaseResource::class, $request, $database, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param ServerDatabase $database
     * @return Response
     */
    public function show(ServerDatabase $database)
    {
        return new ServerDatabaseResource($database);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ServerDatabaseRequest $request
     * @param ServerDatabase $database
     * @return Response
     */
    public function update(ServerDatabaseRequest $request, ServerDatabase $database)
    {
        $database->update($request->only([
            'description'
        ]));

        $database = $this->updateConfig($request, $database);
        $database->save();

        return $this->dispatchJob(DatabaseProvisionJob::class, ServerDatabaseResource::class, $request, $database);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ServerDatabase $user
     * @return Response
     */
    public function destroy(Request $request, ServerDatabase $database)
    {
        return $this->dispatchJob(DatabaseDestroyJob::class, ServerDatabaseResource::class, $request, $database);
    }
}
