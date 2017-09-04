<?php

namespace Modules\ModuleControl\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Process\Process;
use Modules\ModuleControl\Facades\Module;
use Symfony\Component\Console\Input\InputOption;
use Modules\ModuleControl\Facades\ConfigOverride;
use Symfony\Component\Console\Input\InputArgument;

// TODO refact this class.
class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install and configure the module.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->line(PHP_EOL);
        $this->info('           _____ ____  _____ ');
        $this->info('          |  |  |    \|   __|');
        $this->info('          |  |  |  |  |__   |');
        $this->info('          |_____|____/|_____|'.PHP_EOL);
        $this->line('----------------------------------------');
        $this->line('             Module Control');
        $this->line('========================================'.PHP_EOL);

        $modules = Module::getModulesData();
        $modules->each(function ($module) {
            $this->line($module->name);
            $this->line('----------------------------------------');
            $this->installDependencies(collect($module->requires));
            $this->publishConfigurations(collect($module->configs));
            $this->runScripts(collect($module->scripts));
            $this->line('----------------------------------------');
        });

        $this->line(PHP_EOL);
    }

    private function installDependencies(Collection $dependencies)
    {
        if ($dependencies->count() == 0) {
            return $this->line('-- No dependencies to install');
        }

        $this->line('Instaling composer dependencies');
        $this->line(PHP_EOL);
        $dependencies->each(function ($version, $dependency) {
            //$this->runProgress(sprintf('composer require %s %s', $dependency, $version ?: '*'));
        });

        return $this;
    }

    private function publishConfigurations(Collection $configurations)
    {
        if ($configurations->count() == 0) {
            return $this->line('-- No configurations to publish');
        }

        $this->info('This modules need to change your configurations'.PHP_EOL);
        $this->line('Required configurations:');
        $configurations->each(function ($value, $config) {
            $this->line('    '.$config.' => '.$value);
        });

        if ($this->confirm('Do you allow these changes?')) {

            $this->line('Publishing configurations');
            $this->line(PHP_EOL);
            $configurations->each(function ($value, $config) {
                ConfigOverride::write($config, $value);
            });

            return $this;
        }
    }

    private function runScripts(Collection $scripts)
    {
        if ($scripts->count() == 0) {
            return $this->line('-- No scripts to run');
        }

        $this->line('Running scripts');
        $this->line(PHP_EOL);
        $scripts->each(function ($script) {
            $this->runProgress($script);
        });

        return $this;
    }

    private function runProgress($command, $timeout = 3360)
    {
        $process = new Process(sprintf(
            'cd %s && %s',
            base_path(),
            $command
        ));

        return $process
            ->setTimeout($timeout)
            ->run(function ($type, $line) {
                $this->line(sprintf('    %s', $line));
            });
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'Specified module to run only one.'],
        ];
    }
}
