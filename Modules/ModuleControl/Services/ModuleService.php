<?php

namespace Modules\ModuleControl\Services;

use File;
use Route;
use Illuminate\Support\Facades\Config;

class ModuleService
{
    /**
     * module folder path.
     */
    private $modulePath;

    /**
     * Create a new ModuleService instance.
     */
    public function __construct()
    {
        $this->modulePath = config('modules.paths.modules');
    }

    /**
     * Get all modules data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getModulesData()
    {
        return collect(File::directories($this->modulePath))
            ->mapWithKeys(function ($directory) {
                $module = $this->getModuleByPath(basename($directory));

                return [$module->alias => $module];
            });
    }

    /**
     * Get module data from path, case exists.
     *
     * @param string $path
     *
     * @return mixed
     */
    public function getModuleByPath($path)
    {
        if (! File::exists($directory = sprintf('%s/%s', $this->modulePath, $path))) {
            return false;
        }

        $module = json_decode(File::get(sprintf('%s/module.json', $directory)));
        $module->routes = $this->getModuleRoutes($path);

        return $module;
    }

    /**
     * Get module routes, by module name.
     *
     * @param string $moduleName
     *
     * @return \Illuminate\Support\Collection
     */
    public function getModuleRoutes($moduleName)
    {
        return collect(Route::getRoutes())
            ->filter(function ($route) use ($moduleName) {
                $data = explode('\\', $route->getActionName());

                return ($data[0] == 'Modules' && isset($data[1]));
            })->map(function ($route) {
                return [
                    'uri' => $route->uri,
                    'method' => $route->methods()[0],
                    'action' => $route->getActionName(),
                ];
            })->values();
    }
}
