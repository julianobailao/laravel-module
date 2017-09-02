<?php

namespace Modules\ModuleControl\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\ModuleControl\Entities\Action;
use Modules\ModuleControl\Http\Requests\ActionRequest;

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
    public function show(Action $action, $httpStatusCode = 200)
    {
        $action->load('rules');

        return response()->json($action, $httpStatusCode);
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
        $update = $action->id != null;
        $action->fill($request->only('title', 'description'))->save();
        // save action rules
        $rules = collect($request->json('rules'))
            ->each(function ($rule) use ($action) {
                $action
                    ->rules()
                    ->updateOrCreate($rule);
            });

        return $this->show($action, $update === true ? 200 : 201);
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
