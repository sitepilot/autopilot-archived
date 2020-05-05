<?php

namespace App\Http\Controllers;

use App\ServerApp;
use App\Jobs\AppDestroyJob;
use App\Jobs\AppWpLoginJob;
use App\Jobs\AppWpUpdateJob;
use Illuminate\Http\Request;
use App\Jobs\AppProvisionJob;
use App\Jobs\AppWpInstallJob;
use Illuminate\Http\Response;
use App\Jobs\AppCertRequestJob;
use App\Jobs\AppWpCheckStateJob;
use App\Jobs\AppWpSearchReplaceJob;
use App\Http\Requests\ServerAppRequest;
use App\Http\Requests\WpInstallRequest;
use App\Http\Resources\ServerAppResource;
use App\Http\Requests\WpSearchReplaceRequest;
use App\ServerDatabase;

class ServerAppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return ServerAppResource::collection(ServerApp::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ServerAppRequest $request
     * @return Response
     */
    public function store(ServerAppRequest $request)
    {
        $app = ServerApp::create($request->only([
            'name',
            'user_id',
            'description'
        ]));

        $app = $this->updateConfig($request, $app);
        $app->save();

        return $this->dispatchJob(AppProvisionJob::class, ServerAppResource::class, $request, $app, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param ServerApp $app
     * @return Response
     */
    public function show(ServerApp $app)
    {
        return new ServerAppResource($app);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ServerAppRequest $request
     * @param ServerApp $app
     * @return Response
     */
    public function update(ServerAppRequest $request, ServerApp $app)
    {
        $app->update($request->only([
            'description'
        ]));

        $app = $this->updateConfig($request, $app);
        $app->save();

        return $this->dispatchJob(AppProvisionJob::class, ServerAppResource::class, $request, $app);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ServerApp $user
     * @return Response
     */
    public function destroy(Request $request, ServerApp $app)
    {
        return $this->dispatchJob(AppDestroyJob::class, ServerAppResource::class, $request, $app);
    }

    /**
     * Request certificate for the specified resource.
     * 
     * @param Request $request
     * @param ServerApp $app
     * @return Response
     */
    public function certRequest(Request $request, ServerApp $app)
    {
        return $this->dispatchJob(AppCertRequestJob::class, ServerAppResource::class, $request, $app);
    }

    /**
     * Check WordPress state for the specified resource.
     * 
     * @param Request $request
     * @param ServerApp $app
     * @return Response
     */
    public function wpCheckState(Request $request, ServerApp $app)
    {
        return $this->dispatchJob(AppWpCheckStateJob::class, ServerAppResource::class, $request, $app);
    }

    /**
     * Install WordPress for the specified resource.
     * 
     * @param Request $request
     * @param ServerApp $app
     * @return Response
     */
    public function wpInstall(WpInstallRequest $request, ServerApp $app)
    {
        $app->setVar('wordpress.admin_user', $request->input('admin_user'));
        $app->setVar('wordpress.admin_pass', $request->input('admin_pass'));
        $app->setVar('wordpress.admin_email', $request->input('admin_email'));

        $database = ServerDatabase::find($request->input('database_id'));

        $app->setVar('wordpress.db_name', $database->name);
        $app->setVar('wordpress.db_user', $database->user->getVar('name'));
        $app->setVar('wordpress.db_pass', $database->user->getVar('mysql_password'));
        $app->setVar('wordpress.db_host', '127.0.0.1');

        $app->save();

        return $this->dispatchJob(AppWpInstallJob::class, ServerAppResource::class, $request, $app);
    }

    /**
     * Generate WordPress login link for the specified resource.
     * 
     * @param Request $request
     * @param ServerApp $app
     * @return Response
     */
    public function wpLogin(Request $request, ServerApp $app)
    {
        return $this->dispatchJob(AppWpLoginJob::class, ServerAppResource::class, $request, $app);
    }

    /**
     * Search and replace in WordPress database for the specified resource.
     * 
     * @param WpSearchReplaceRequest $request
     * @param ServerApp $app
     * @return Response
     */
    public function wpSearchReplace(WpSearchReplaceRequest $request, ServerApp $app)
    {
        return $this->dispatchJob(AppWpSearchReplaceJob::class, ServerAppResource::class, $request, $app);
    }

    /**
     * Update WordPress for the specified resource.
     * 
     * @param Request $request
     * @param ServerApp $app
     * @return Response
     */
    public function wpUpdate(Request $request, ServerApp $app)
    {
        return $this->dispatchJob(AppWpUpdateJob::class, ServerAppResource::class, $request, $app);
    }
}
