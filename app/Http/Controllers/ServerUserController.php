<?php

namespace App\Http\Controllers;

use App\ServerUser;
use App\Jobs\UserTestJob;
use App\Jobs\UserDestroyJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Jobs\UserProvisionJob;
use App\Http\Requests\ServerUserRequest;
use App\Http\Resources\ServerUserResource;

class ServerUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return ServerUserResource::collection(ServerUser::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ServerUserRequest $request
     * @return Response
     */
    public function store(ServerUserRequest $request)
    {
        $user = ServerUser::create($request->only([
            'host_id',
            'client_id',
            'description'
        ]));

        $user = $this->updateConfig($request, $user);
        $user->save();

        return $this->dispatchJob(UserProvisionJob::class, ServerUserResource::class, $request, $user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param ServerUser $user
     * @return Response
     */
    public function show(ServerUser $user)
    {
        return new ServerUserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ServerUserRequest $request
     * @param ServerUser $user
     * @return Response
     */
    public function update(ServerUserRequest $request, ServerUser $user)
    {
        $user->update($request->only([
            'description'
        ]));

        $user = $this->updateConfig($request, $user);
        $user->save();

        return $this->dispatchJob(UserProvisionJob::class, ServerUserResource::class, $request, $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ServerUser $user
     * @return Response
     */
    public function destroy(Request $request, ServerUser $user)
    {
        return $this->dispatchJob(UserDestroyJob::class, ServerUserResource::class, $request, $user);
    }

    /**
     * Provision the specified resource.
     * 
     * @param Request $request
     * @param ServerUser $user
     * @return Response
     */
    public function provision(Request $request, ServerUser $user)
    {
        return $this->dispatchJob(UserProvisionJob::class, ServerUserResource::class, $request, $user);
    }

    /**
     * Test the specified resource.
     * 
     * @param Request $request
     * @param ServerUser $user
     * @return Response
     */
    public function test(Request $request, ServerUser $user)
    {
        return $this->dispatchJob(UserTestJob::class, ServerUserResource::class, $request, $user);
    }
}
