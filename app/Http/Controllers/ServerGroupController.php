<?php

namespace App\Http\Controllers;

use App\ServerGroup;
use Illuminate\Http\Response;
use App\Http\Requests\ServerGroupRequest;
use App\Http\Resources\ServerGroupResource;

class ServerGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return ServerGroupResource::collection(ServerGroup::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ServerGroupRequest $request
     * @return Response
     */
    public function store(ServerGroupRequest $request)
    {
        $group = ServerGroup::create($request->only([
            'name',
            'description'
        ]));

        $this->updateConfig($request, $group)->save();

        return new ServerGroupResource($group);
    }

    /**
     * Display the specified resource.
     *
     * @param ServerGroup $group
     * @return Response
     */
    public function show(ServerGroup $group)
    {
        return new ServerGroupResource($group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ServerGroupRequest $request
     * @param ServerGroup $group
     * @return Response
     */
    public function update(ServerGroupRequest $request, ServerGroup $group)
    {
        $group->update($request->only([
            'description'
        ]));
        
        $this->updateConfig($request, $group)->save();

        return new ServerGroupResource($group);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ServerGroup $group
     * @return Response
     */
    public function destroy(ServerGroup $group)
    {
        $group->delete();

        return response()->json(null, 204);
    }
}
