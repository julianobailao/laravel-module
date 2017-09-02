<?php

namespace Modules\ModuleControl\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\ModuleControl\Entities\Action;
use odules\ModuleControl\Http\Request\ActionRequest;

class ActionController extends Controller
{
    /**
     * Display a list of Actions.
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $data = Action::search($request->get('search'))
            ->paginate($request->get('perPage') ?: 15);

        return response()->json($data);
    }

    /**
     * Display a specified Action data.
     *
     * @param  Action $action
     * @return Response
     */
    public function show(Action $action)
    {
        return response()->json($action);
    }

    /**
     * Create a new Action.
     *
     * @param  ActionRequest $request
     * @return Response
     */
    public function store(ActionRequest $request)
    {
        return $this->save(new Action(), $request);
    }

    /**
     * Update a specified Action.
     *
     * @param  Action        $action
     * @param  ActionRequest $request
     * @return Response
     */
    public function update(Action $action, ActionRequest $request)
    {
        return $this->save($action, $request);
    }

    /**
     * Persist the request data on the database.
     *
     * @param  Action        $action
     * @param  ActionRequest $request
     * @return Response
     */
    private function save(Action $action, ActionRequest $request)
    {
        $action->create($request->only('title', 'description'));
        $action->roles()->attach($request->only('roles'));

        return $this->show($action);
    }

    /**
     * Delete a specified Action
     *
     * @param  Action $action
     * @return Response
     */
    public function destroy(Action $action)
    {
        $action->delete();

        return response(null, 204);
    }
}
