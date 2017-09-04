<?php

namespace Modules\ModuleControl\Traits;

use Illuminate\Foundation\Testing\DatabaseMigrations as BaseDatabaseMigrations;

trait ModuleDatabaseMigrations
{
    use BaseDatabaseMigrations {
        runDatabaseMigrations as protected traitMigrate;
    }

    /**
     * Define hooks to migrate the database before and after each test.
     *
     * @return void
     */
    public function runDatabaseMigrations()
    {
        $this->traitMigrate();

        $this->artisan('module:migrate');

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('module:migrate-rollback');
        });
    }
}
