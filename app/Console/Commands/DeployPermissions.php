<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DeployPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $base = collect([
            'create',
            'view',
            'update',
            'soft delete',
            'delete',
            'restore',
        ]);

        $baseSingular = collect([
            'view any',
        ]);

        $models = collect([
            'user',
            'experiment',
            'node',
        ]);

        $modelPermissions =
            $base->crossJoin($models->map(fn ($model) => str($model)->plural()))
                ->mapSpread(fn ($base, $model) => ['name' => "{$base} {$model}", 'guard_name' => 'web']);
        $singularModelPermissions =
            $baseSingular->crossJoin($models)
                ->mapSpread(fn ($base, $model) => ['name' => "{$base} {$model}", 'guard_name' => 'web']);

        $otherPermissions = collect([
            'access filament',
        ])->map(fn ($permission) => ['name' => $permission, 'guard_name' => 'web']);

        $allPermissions =
            $modelPermissions
                ->merge($singularModelPermissions)
                ->merge($otherPermissions)
                ->toArray();
        Permission::upsert($allPermissions, ['name', 'guard_name']);

        $adminRole = Role::createOrFirst(['name' => 'admin']);
        $runnerRole = Role::createOrFirst(['name' => 'runner']);

        $adminRole->syncPermissions(Permission::whereNotIn('name', [
            //
        ])->get());
        $runnerRole->syncPermissions(Permission::whereIn('name', [
            'create experiments',
            'create nodes',

            'update nodes',
        ])->get());

        $admin = User::firstWhere('name', 'admin');
        if ($admin) {
            $admin->assignRole('admin');
        }

        $runner = User::firstWhere('name', 'runner');
        if ($runner) {
            $runner->assignRole('runner');
        }
    }
}
