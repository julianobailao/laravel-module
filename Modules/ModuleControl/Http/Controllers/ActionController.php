<?php

namespace Modules\ModuleControl\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\ModuleControl\Entities\Action;

class ActionController extends Controller
{
    public function index()
    {
        $data = Action::paginate();

        return response()->json($data);
    }

    public function show(Action $action)
    {
        return response()->json($action);
    }

    public function store(Request $request)
    {
        return $this->save(new Action(), $request);
    }

    public function update(Action $action, Request $request)
    {
        return $this->save($action, $request);
    }

    private function save(Action $action, $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:actions,title',
        ]);

        return response()->json($action->create($request->all()));
    }

    public function destroy(Action $action)
    {
        $action->delete();

        return response(null, 204);
    }
}
