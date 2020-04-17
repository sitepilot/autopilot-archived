<?php

namespace App\Traits;

use Laravel\Nova\Actions\Action;
use Laravel\Nova\Http\Requests\ActionRequest;

trait QueuedAction
{
    /**
     * Execute the action for the given request.
     *
     * @param  ActionRequest  $request
     * @return mixed
     * @throws MissingActionHandlerException
     */
    public function handleRequest(ActionRequest $request)
    {
        parent::handleRequest($request);

        return Action::message('Action was scheduled successfully and will be processed soon.');
    }
}