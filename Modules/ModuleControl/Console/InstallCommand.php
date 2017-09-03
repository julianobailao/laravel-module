<?php

namespace Modules\ModuleControl\Console;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Modules\ModuleControl\Facades\Module;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

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
        $this->info(' _____ ____  _____ ');
        $this->info('|  |  |    \|   __|');
        $this->info('|  |  |  |  |__   |');
        $this->info('|_____|____/|_____|'.PHP_EOL);
        $this->line('------------------------------------');
        $this->line('Instaling modules');
        $this->line('------------------------------------'.PHP_EOL);

        $modules = Module::getModulesData();

        $modules->each(function ($module) {
            $this->line(sprintf('Instaling data from %s', $module->name));
            $this->line('Instaling dependencies');

            // foreach ($module->requires as $dependency => $version) {
            //     $process = new Process(sprintf(
            //         'cd %s && composer require %s %s',
            //         base_path(),
            //         $dependency,
            //         $version ?: '*'
            //     ));

            //     $process->setTimeout($timeout = 3360);

            //     $process->run(function ($type, $line) {
            //         $this->line($line);
            //     });
            // }

            $this->line('Publishing configurations');

            $override = new \Modules\ModuleControl\Services\ConfigOverrideService();

            foreach ($module->configs as $config => $value) {
                $data = explode('.', $config);
                dd($override->write(array_shift($data), $data, $value));

                $writeConfig = new \October\Rain\Config\Rewrite;
                $writeConfig->toFile(
                    config_path(sprintf('%s.php', array_shift($data))), [
                        implode('.', $data) => $value
                    ]);
            }

            $this->line('Runing scripts');

            foreach ($module->scripts as $scripts) {
                $process = new Process(sprintf(
                    'cd %s && %s',
                    base_path(),
                    $scripts
                ));

                $process->setTimeout($timeout = 3360);

                $process->run(function ($type, $line) {
                    $this->line($line);
                });
            }
        });

        $this->line(PHP_EOL);
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
