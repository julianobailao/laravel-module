<?php

namespace Modules\ModuleControl\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\ModuleControl\Entities\Action;

class ActionController extends Controller
{
    public function index()
    {
        $data = Action::all()
            ->paginate();

        return response()->json([$data]);
    }
}
