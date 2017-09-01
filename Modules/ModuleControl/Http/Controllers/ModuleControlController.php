<?php

namespace Modules\ModuleControl\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ModuleControl\Facades\Module;

class ModuleControlController extends Controller
{
    /**
     * Display a listing of modules.
     *
     * @return Response
     */
    public function index()
    {
        return response()->json(Module::getModulesData());
    }

    /**
     * Show the specified module.
     *
     * @param string $path
     *
     * @return Response
     */
    public function show($path)
    {
        $module = Module::getModuleByPath($path);

        if ($module == false) {
            abort(404);
        }

        return response()->json($module);
    }

    // TODO make a method to install and uninstall modules, inside a repository.
}
